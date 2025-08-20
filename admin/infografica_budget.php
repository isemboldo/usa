<?php
// 1. Definiamo la costante di sicurezza e avviamo la sessione.
define('ABSPATH', true);
session_start();

// 2. Controllo di Sicurezza.
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: /usa/admin/login.php');
    exit;
}

// 3. Includiamo la configurazione e ci connettiamo al database.
require_once '../page/config.php';
try {
    $pdo = connect_db();
} catch (PDOException $e) {
    die("Impossibile connettersi al database: " . $e->getMessage());
}

// 4. Carichiamo dati di supporto
$tassi_cambio_raw = $pdo->query("SELECT valuta, tasso_a_chf FROM tassi_cambio")->fetchAll(PDO::FETCH_KEY_PAIR);
$tassi_cambio = array_merge(['CHF' => 1.0], $tassi_cambio_raw);
$categorie = ['Voli','Alloggi','Pasti','Attrazioni','Trasporti','Varie'];

// --- Logica di Calcolo per l'Infografica ---

$luoghi_parti_map = [
    1 => 1, 2 => 2, 3 => 2, 4 => 2, 5 => 2, 6 => 2, 7 => 2, 8 => 2, 9 => 3
];

$spese_raw = $pdo->query("
    SELECT s.*, g.parte_id 
    FROM spese s 
    LEFT JOIN giorni g ON s.giorno_id = g.id
")->fetchAll(PDO::FETCH_ASSOC);

$costi_per_categoria = array_fill_keys($categorie, 0);
$costo_totale_viaggio = 0;
$spesa_maggiore = ['categoria' => 'N/D', 'importo' => 0];

$costi_per_parte = [1 => 0, 2 => 0, 3 => 0]; // 1: NY, 2: Sud, 3: Orlando
$giorni_per_parte = [1 => 5, 2 => 8, 3 => 8]; // Durata di ogni tappa

foreach ($spese_raw as $spesa) {
    $importo = floatval($spesa['importo_reale']) > 0 ? floatval($spesa['importo_reale']) : (floatval($spesa['importo_preventivo']) > 0 ? floatval($spesa['importo_preventivo']) : floatval($spesa['importo_stimato']));
    
    if ($importo > 0) {
        $importo_in_chf = $importo * ($tassi_cambio[$spesa['valuta']] ?? 1);
        $costo_totale_viaggio += $importo_in_chf;

        if (isset($costi_per_categoria[$spesa['categoria']])) {
            $costi_per_categoria[$spesa['categoria']] += $importo_in_chf;
        }

        $parte_assegnata = null;
        if (!empty($spesa['parte_id'])) {
            $parte_assegnata = $spesa['parte_id'];
        } elseif (!empty($spesa['luogo_id']) && isset($luoghi_parti_map[$spesa['luogo_id']])) {
            $parte_assegnata = $luoghi_parti_map[$spesa['luogo_id']];
        }

        if ($parte_assegnata && isset($costi_per_parte[$parte_assegnata])) {
            $costi_per_parte[$parte_assegnata] += $importo_in_chf;
        }
    }
}

// Calcolo costo medio giornaliero
$totale_giorni = 21;
$costo_medio_giorno = $totale_giorni > 0 ? $costo_totale_viaggio / $totale_giorni : 0;

// Calcolo costo medio per luogo
$costo_medio_ny = $giorni_per_parte[1] > 0 ? $costi_per_parte[1] / $giorni_per_parte[1] : 0;
$costo_medio_sud = $giorni_per_parte[2] > 0 ? $costi_per_parte[2] / $giorni_per_parte[2] : 0;
$costo_medio_orlando = $giorni_per_parte[3] > 0 ? $costi_per_parte[3] / $giorni_per_parte[3] : 0;

// Trova la spesa maggiore
if ($costo_totale_viaggio > 0) {
    arsort($costi_per_categoria);
    $spesa_maggiore['categoria'] = key($costi_per_categoria);
    $spesa_maggiore['importo'] = current($costi_per_categoria);
}

// Prepara i dati per i grafici JavaScript
$chart_data = [
    'labels' => array_keys($costi_per_categoria),
    'values' => array_values($costi_per_categoria)
];

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infografica Budget - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/usa/assets/css/style.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-stone-100">

    <header class="bg-white text-stone-800 shadow-lg">
        <div class="main-container flex justify-between items-center h-16">
            <h1 class="text-xl font-bold">Cruscotto Visivo del Budget</h1>
            <div>
                <a href="index.php" class="text-sm font-semibold text-purple-600 hover:underline">&larr; Torna alla Dashboard</a>
            </div>
        </div>
    </header>

    <main class="main-container py-12">

        <!-- Cruscotto Visivo -->
        <section>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Colonna Sinistra: Grafici -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-bold text-stone-800 mb-4">Come Spendiamo i Nostri Soldi (%)</h3>
                        <div class="h-80"><canvas id="pieChart"></canvas></div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-bold text-stone-800 mb-4">Le Categorie a Confronto (CHF)</h3>
                        <div class="h-80"><canvas id="barChart"></canvas></div>
                    </div>
                </div>
                <!-- Colonna Destra: Metriche Chiave -->
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-xl shadow-lg text-center">
                        <h3 class="text-sm font-semibold text-stone-500 uppercase tracking-wider">🏆 La Spesa Maggiore</h3>
                        <p class="text-3xl font-bold text-purple-600 mt-2"><?php echo htmlspecialchars($spesa_maggiore['categoria']); ?></p>
                        <p class="text-xl font-semibold text-stone-700"><?php echo number_format($spesa_maggiore['importo'], 2, ',', "'"); ?> CHF</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-lg text-center">
                        <h3 class="text-sm font-semibold text-stone-500 uppercase tracking-wider">📅 Costo Medio per Giorno</h3>
                        <p class="text-4xl font-bold text-stone-800 mt-2"><?php echo number_format($costo_medio_giorno, 2, ',', "'"); ?> CHF</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-center text-sm font-semibold text-stone-500 uppercase tracking-wider mb-3">Costo Medio per Luogo</h3>
                        <ul class="space-y-3">
                            <li class="flex justify-between items-center">
                                <span class="font-bold">🗽 New York</span>
                                <span class="font-bold text-purple-600"><?php echo number_format($costo_medio_ny, 2, ',', "'"); ?> CHF/giorno</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="font-bold">🚗 Il Sud</span>
                                <span class="font-bold text-purple-600"><?php echo number_format($costo_medio_sud, 2, ',', "'"); ?> CHF/giorno</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="font-bold">🎢 Orlando</span>
                                <span class="font-bold text-purple-600"><?php echo number_format($costo_medio_orlando, 2, ',', "'"); ?> CHF/giorno</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = <?php echo json_encode($chart_data); ?>;
            // Nuova palette di colori più estesa e intuitiva
            const chartColors = ['#8b5cf6', '#10b981', '#f59e0b', '#ef4444', '#3b82f6', '#ec4899']; // Viola, Verde, Ambra, Rosso, Blu, Rosa

            // Grafico a Torta
            const pieCtx = document.getElementById('pieChart').getContext('2d');
            new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Spese per Categoria',
                        data: chartData.values,
                        backgroundColor: chartColors,
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });

            // Grafico a Barre
            const barCtx = document.getElementById('barChart').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Costo Totale (CHF)',
                        data: chartData.values,
                        backgroundColor: chartColors, // Usiamo la stessa palette
                        borderColor: chartColors.map(c => c + 'B3'), // Aggiunge un po' di trasparenza al bordo
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
