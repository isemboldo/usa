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
$partecipanti = ['Federico', 'Paola', 'Elanor', 'Francesca', 'Emilio', 'Lucrezia'];
$tassi_cambio_raw = $pdo->query("SELECT valuta, tasso_a_chf FROM tassi_cambio")->fetchAll(PDO::FETCH_KEY_PAIR);
$tassi_cambio = array_merge(['CHF' => 1.0], $tassi_cambio_raw); // Aggiungiamo CHF con tasso 1

// --- Logica di Calcolo per il Rendiconto ---

// A. Calcolo del Costo Previsto di ogni spesa in CHF
$spese = $pdo->query("SELECT * FROM spese")->fetchAll(PDO::FETCH_ASSOC);
$costo_totale_previsto = 0;
$costo_totale_stimato = 0;
$costi_per_persona = array_fill_keys($partecipanti, []);
$quote_per_persona = array_fill_keys($partecipanti, 0);

foreach ($spese as $spesa) {
    $importo_previsto = floatval($spesa['importo_preventivo']) > 0 ? floatval($spesa['importo_preventivo']) : floatval($spesa['importo_stimato']);
    $costo_totale_stimato += floatval($spesa['importo_stimato']) * ($tassi_cambio[$spesa['valuta']] ?? 1);
    
    if ($importo_previsto > 0) {
        $importo_in_chf = $importo_previsto * ($tassi_cambio[$spesa['valuta']] ?? 1);
        $costo_totale_previsto += $importo_in_chf;
        
        $diviso_per = array_filter(array_map('trim', explode(',', $spesa['diviso_per'])));
        if (!empty($diviso_per)) {
            $quota_individuale = $importo_in_chf / count($diviso_per);
            foreach ($diviso_per as $partecipante) {
                if (in_array($partecipante, $partecipanti)) {
                    $quote_per_persona[$partecipante] += $quota_individuale;
                    $costi_per_persona[$partecipante][] = [
                        'descrizione' => $spesa['descrizione'],
                        'categoria' => $spesa['categoria'],
                        'quota' => $quota_individuale
                    ];
                }
            }
        }
    }
}

// B. Calcolo dei Pagamenti e Saldi
$pagamenti_raw = $pdo->query("SELECT * FROM pagamenti")->fetchAll(PDO::FETCH_ASSOC);
$acconti_versati = array_fill_keys($partecipanti, 0);
foreach ($pagamenti_raw as $pagamento) {
    $importo_in_chf = floatval($pagamento['importo']) * ($tassi_cambio[$pagamento['valuta']] ?? 1);
    if (in_array($pagamento['partecipante'], $partecipanti)) {
        $acconti_versati[$pagamento['partecipante']] += $importo_in_chf;
    }
}

$saldi_finali = [];
foreach ($partecipanti as $p) {
    $saldi_finali[$p] = $acconti_versati[$p] - $quote_per_persona[$p];
}

// C. Raggruppiamo i costi per categoria per ogni persona
$costi_raggruppati = [];
foreach ($costi_per_persona as $persona => $costi) {
    foreach ($costi as $costo) {
        $costi_raggruppati[$persona][$costo['categoria']][] = $costo;
    }
}

$scostamento = $costo_totale_previsto - $costo_totale_stimato;

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisi Budget Pre-Viaggio - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/usa/assets/css/style.css">
</head>
<body class="bg-stone-100">

    <header class="bg-white text-stone-800 shadow-lg">
        <div class="main-container flex justify-between items-center h-16">
            <h1 class="text-xl font-bold">Analisi Budget Pre-Viaggio</h1>
            <div>
                <a href="index.php" class="text-sm font-semibold text-purple-600 hover:underline">&larr; Torna alla Dashboard</a>
            </div>
        </div>
    </header>

    <main class="main-container py-12">

        <!-- Analisi Scostamento -->
        <section class="mb-8 bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-3xl font-bold text-stone-900 mb-4">Stato del Budget</h2>
            <div class="text-center">
                <p class="text-lg text-stone-600">Il costo totale previsto del viaggio è di</p>
                <p class="text-5xl font-bold text-purple-600 my-2"><?php echo number_format($costo_totale_previsto, 2, ',', "'"); ?> CHF</p>
                <?php if ($scostamento > 0): ?>
                    <p class="text-red-600 font-semibold">Stiamo spendendo <?php echo number_format($scostamento, 2, ',', "'"); ?> CHF in più rispetto alla stima iniziale.</p>
                <?php else: ?>
                    <p class="text-green-600 font-semibold">Stiamo risparmiando <?php echo number_format(abs($scostamento), 2, ',', "'"); ?> CHF rispetto alla stima iniziale.</p>
                <?php endif; ?>
                 <p class="text-xs text-stone-400 mt-2">(Basato su preventivi dove disponibili, altrimenti su stime. Tassi di cambio applicati: 1 USD = <?php echo $tassi_cambio['USD'] ?? 'N/D'; ?> CHF, 1 EUR = <?php echo $tassi_cambio['EUR'] ?? 'N/D'; ?> CHF)</p>
            </div>
        </section>

        <!-- Rendiconto Personale -->
        <section class="mb-8">
            <h2 class="text-3xl font-bold text-stone-900 mb-4">Rendiconto Personale Dettagliato</h2>
            <div class="space-y-4">
                <?php foreach ($partecipanti as $p): ?>
                    <div class="bg-white rounded-xl shadow-lg">
                        <div class="p-4 flex justify-between items-center">
                            <h3 class="text-2xl font-bold"><?php echo $p; ?></h3>
                            <div class="text-right">
                                <p class="text-sm text-stone-500">Costo Totale Previsto</p>
                                <p class="text-2xl font-bold text-purple-600"><?php echo number_format($quote_per_persona[$p], 2, ',', "'"); ?> CHF</p>
                            </div>
                        </div>
                        <div class="border-t border-stone-200 px-4">
                            <?php if (isset($costi_raggruppati[$p])): ?>
                                <?php foreach ($costi_raggruppati[$p] as $categoria => $costi_cat): ?>
                                    <div class="accordion-item">
                                        <button class="accordion-header">
                                            <span><strong><?php echo $categoria; ?>:</strong> <?php echo number_format(array_sum(array_column($costi_cat, 'quota')), 2, ',', "'"); ?> CHF</span>
                                            <span class="arrow">▼</span>
                                        </button>
                                        <div class="accordion-content">
                                            <ul class="list-disc list-inside text-sm text-stone-600 pt-2 pb-4 px-4">
                                                <?php foreach ($costi_cat as $costo_dettaglio): ?>
                                                    <li><?php echo htmlspecialchars($costo_dettaglio['descrizione']); ?>: <em><?php echo number_format($costo_dettaglio['quota'], 2, ',', "'"); ?> CHF</em></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        
        <!-- Acconti e Saldi -->
        <section>
            <h2 class="text-3xl font-bold text-stone-900 mb-4">Acconti e Saldi</h2>
            <div class="bg-white rounded-xl shadow-lg overflow-x-auto">
                 <table class="min-w-full divide-y divide-stone-200">
                    <thead class="bg-stone-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Partecipante</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-stone-500 uppercase tracking-wider">Totale Dovuto</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-stone-500 uppercase tracking-wider">Acconti Versati</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-stone-500 uppercase tracking-wider">Saldo da Versare</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-stone-200">
                        <?php foreach ($partecipanti as $p): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-bold"><?php echo $p; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right"><?php echo number_format($quote_per_persona[$p], 2, ',', "'"); ?> CHF</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-green-600"><?php echo number_format($acconti_versati[$p], 2, ',', "'"); ?> CHF</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-red-600">
                                    <?php echo number_format($quote_per_persona[$p] - $acconti_versati[$p], 2, ',', "'"); ?> CHF
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
             <!-- Aggiungeremo qui il form per inserire i pagamenti -->
        </section>

    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accordionHeaders = document.querySelectorAll('.accordion-header');
            accordionHeaders.forEach(header => {
                header.addEventListener('click', () => {
                    const content = header.nextElementSibling;
                    const arrow = header.querySelector('.arrow');
                    content.classList.toggle('open');
                    arrow.textContent = content.classList.contains('open') ? '▲' : '▼';
                });
            });
        });
    </script>
    <style>
        .accordion-header { width: 100%; display: flex; justify-content: space-between; padding: 1rem 0; cursor: pointer; font-size: 1.125rem; }
        .accordion-content { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
        .accordion-content.open { max-height: 500px; /* Valore abbastanza grande */ }
    </style>
</body>
</html>
