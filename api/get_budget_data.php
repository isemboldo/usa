<?php
// Impostiamo l'header per comunicare che la risposta sarà in formato JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Permette l'accesso da qualsiasi dominio (utile per lo sviluppo)

// 1. Definiamo la costante di sicurezza.
define('ABSPATH', true);

// 2. Includiamo la configurazione e ci connettiamo al database.
// Il percorso sale di una cartella (da /api/ a /usa/) per trovare /page/config.php
require_once '../page/config.php';
try {
    $pdo = connect_db();
} catch (PDOException $e) {
    // In caso di errore, restituiamo un JSON di errore
    echo json_encode(['error' => 'Impossibile connettersi al database.']);
    exit;
}

// 3. Carichiamo dati di supporto
$partecipanti = ['Federico', 'Paola', 'Elanor', 'Francesca', 'Emilio', 'Lucrezia'];
$tassi_cambio_raw = $pdo->query("SELECT valuta, tasso_a_chf FROM tassi_cambio")->fetchAll(PDO::FETCH_KEY_PAIR);
$tassi_cambio = array_merge(['CHF' => 1.0], $tassi_cambio_raw);

// --- Inizio Logica di Calcolo ---

// A. Calcolo del Costo Previsto di ogni spesa in CHF
$spese = $pdo->query("SELECT * FROM spese")->fetchAll(PDO::FETCH_ASSOC);
$costo_totale_previsto = 0;
$costo_totale_stimato = 0;
$costi_per_persona = array_fill_keys($partecipanti, []);
$quote_per_persona = array_fill_keys($partecipanti, 0);

foreach ($spese as $spesa) {
    // Logica "preventivo vince su stima"
    $importo_previsto = floatval($spesa['importo_preventivo']) > 0 ? floatval($spesa['importo_preventivo']) : floatval($spesa['importo_stimato']);
    $importo_stimato_chf = floatval($spesa['importo_stimato']) * ($tassi_cambio[$spesa['valuta']] ?? 1);
    $costo_totale_stimato += $importo_stimato_chf;
    
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

// B. Calcolo dei Pagamenti
$pagamenti_raw = $pdo->query("SELECT * FROM pagamenti")->fetchAll(PDO::FETCH_ASSOC);
$acconti_versati = array_fill_keys($partecipanti, 0);
foreach ($pagamenti_raw as $pagamento) {
    $importo_in_chf = floatval($pagamento['importo']) * ($tassi_cambio[$pagamento['valuta']] ?? 1);
    if (in_array($pagamento['partecipante'], $partecipanti)) {
        $acconti_versati[$pagamento['partecipante']] += $importo_in_chf;
    }
}

// C. Raggruppiamo i costi per categoria per ogni persona
$costi_raggruppati = [];
foreach ($costi_per_persona as $persona => $costi) {
    if (!empty($costi)) {
        foreach ($costi as $costo) {
            $costi_raggruppati[$persona][$costo['categoria']][] = $costo;
        }
        ksort($costi_raggruppati[$persona]);
    }
}

$scostamento = $costo_totale_previsto - $costo_totale_stimato;

// --- Fine Logica di Calcolo ---

// 4. Prepariamo l'array finale da restituire come JSON
$output = [
    'stato_budget' => [
        'costo_totale_previsto' => $costo_totale_previsto,
        'scostamento' => $scostamento,
        'tassi_cambio' => $tassi_cambio
    ],
    'rendiconto_personale' => []
];

foreach ($partecipanti as $p) {
    $output['rendiconto_personale'][] = [
        'nome' => $p,
        'totale_dovuto' => $quote_per_persona[$p],
        'acconti_versati' => $acconti_versati[$p],
        'saldo' => $quote_per_persona[$p] - $acconti_versati[$p],
        'dettaglio_spese' => $costi_raggruppati[$p] ?? []
    ];
}

// 5. Stampiamo l'output in formato JSON
echo json_encode($output, JSON_PRETTY_PRINT);
?>
