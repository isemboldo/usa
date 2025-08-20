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

// 4. Lista predefinita dei partecipanti (per coerenza)
$partecipanti = ['Federico', 'Paola', 'Elanor', 'Francesca', 'Emilio', 'Lucrezia'];

// 5. Estraiamo tutte le spese dal database
// Per ora, ipotizziamo che tutte le spese siano in CHF per semplicità di calcolo.
$spese = $pdo->query("SELECT * FROM spese WHERE importo_reale > 0")->fetchAll(PDO::FETCH_ASSOC);

// --- Inizio Logica di Calcolo ---
$totale_viaggio = 0;
$speso_da = array_fill_keys($partecipanti, 0);
$saldi = array_fill_keys($partecipanti, 0);

foreach ($spese as $spesa) {
    $importo = floatval($spesa['importo_reale']);
    $totale_viaggio += $importo;

    // Aggiungi l'importo a chi ha pagato
    if (!empty($spesa['pagato_da']) && in_array($spesa['pagato_da'], $partecipanti)) {
        $speso_da[$spesa['pagato_da']] += $importo;
    }

    // Suddividi il costo tra i partecipanti
    $diviso_per = array_filter(array_map('trim', explode(',', $spesa['diviso_per'])));
    if (!empty($diviso_per)) {
        $quota_spesa = $importo / count($diviso_per);
        foreach ($diviso_per as $partecipante) {
            if (in_array($partecipante, $partecipanti)) {
                $saldi[$partecipante] -= $quota_spesa;
            }
        }
    }
}

// Aggiungi quanto speso al saldo di ogni partecipante
foreach ($speso_da as $persona => $importo_speso) {
    $saldi[$persona] += $importo_speso;
}

$quota_ideale = count($partecipanti) > 0 ? $totale_viaggio / count($partecipanti) : 0;

// --- Algoritmo per Pareggiare i Conti ---
$debitori = [];
$creditori = [];
foreach ($saldi as $persona => $saldo) {
    if ($saldo < 0) {
        $debitori[$persona] = abs($saldo);
    } elseif ($saldo > 0) {
        $creditori[$persona] = $saldo;
    }
}

$transazioni = [];
while (!empty($debitori) && !empty($creditori)) {
    arsort($debitori);
    arsort($creditori);

    $debitore_max = key($debitori);
    $debito_max = current($debitori);
    $creditore_max = key($creditori);
    $credito_max = current($creditori);

    $importo_transazione = min($debito_max, $credito_max);

    $transazioni[] = [
        'da' => $debitore_max,
        'a' => $creditore_max,
        'importo' => $importo_transazione
    ];

    $debitori[$debitore_max] -= $importo_transazione;
    $creditori[$creditore_max] -= $importo_transazione;

    if ($debitori[$debitore_max] < 0.01) {
        unset($debitori[$debitore_max]);
    }
    if ($creditori[$creditore_max] < 0.01) {
        unset($creditori[$creditore_max]);
    }
}
// --- Fine Logica di Calcolo ---

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riepilogo Conti - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/usa/assets/css/style.css">
</head>
<body class="bg-stone-100">

    <header class="bg-white text-stone-800 shadow-lg">
        <div class="main-container flex justify-between items-center h-16">
            <h1 class="text-xl font-bold">Riepilogo Conti</h1>
            <div>
                <a href="index.php" class="text-sm font-semibold text-purple-600 hover:underline">&larr; Torna alla Dashboard</a>
            </div>
        </div>
    </header>

    <main class="main-container py-12">
        
        <!-- Riepilogo Generale -->
        <section class="mb-8 bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-3xl font-bold text-stone-900 mb-4">Analisi Contabile</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div>
                    <h3 class="text-sm font-semibold text-stone-500 uppercase tracking-wider">Costo Totale Viaggio</h3>
                    <p class="text-4xl font-bold text-stone-800 mt-2"><?php echo number_format($totale_viaggio, 2, ',', "'"); ?> CHF</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-stone-500 uppercase tracking-wider">N. Partecipanti</h3>
                    <p class="text-4xl font-bold text-stone-800 mt-2"><?php echo count($partecipanti); ?></p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-stone-500 uppercase tracking-wider">Quota Ideale / Persona</h3>
                    <p class="text-4xl font-bold text-purple-600 mt-2"><?php echo number_format($quota_ideale, 2, ',', "'"); ?> CHF</p>
                </div>
            </div>
        </section>

        <!-- Analisi per Partecipante -->
        <section class="mb-8">
            <h2 class="text-3xl font-bold text-stone-900 mb-4">Saldi Individuali</h2>
            <div class="bg-white rounded-xl shadow-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-200">
                    <thead class="bg-stone-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Partecipante</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-stone-500 uppercase tracking-wider">Totale Speso</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-stone-500 uppercase tracking-wider">Quota Ideale</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-stone-500 uppercase tracking-wider">Saldo</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-stone-200">
                        <?php foreach ($partecipanti as $p): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-bold"><?php echo $p; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right"><?php echo number_format($speso_da[$p], 2, ',', "'"); ?> CHF</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right"><?php echo number_format($quota_ideale, 2, ',', "'"); ?> CHF</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-bold <?php echo ($saldi[$p] >= 0) ? 'text-green-600' : 'text-red-600'; ?>">
                                    <?php echo number_format($saldi[$p], 2, ',', "'"); ?> CHF
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Transazioni per Pareggiare -->
        <section>
            <h2 class="text-3xl font-bold text-stone-900 mb-4">Come Pareggiare i Conti</h2>
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <?php if (empty($transazioni)): ?>
                    <p class="text-stone-600 text-center">Tutti i conti sono già in pari!</p>
                <?php else: ?>
                    <ul class="space-y-4">
                        <?php foreach ($transazioni as $t): ?>
                            <li class="flex items-center justify-center text-lg">
                                <span class="font-bold text-red-600"><?php echo htmlspecialchars($t['da']); ?></span>
                                <span class="mx-2">deve dare</span>
                                <span class="font-bold text-purple-600 mx-2"><?php echo number_format($t['importo'], 2, ',', "'"); ?> CHF</span>
                                <span class="mx-2">a</span>
                                <span class="font-bold text-green-600"><?php echo htmlspecialchars($t['a']); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </section>

    </main>

</body>
</html>
