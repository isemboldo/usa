<?php
// Impostiamo l'header per comunicare che la risposta sarà in formato JSON
header('Content-Type: application/json');

// Definiamo la costante di sicurezza per poter includere il file di configurazione
define('ABSPATH', true);
require_once '../page/config.php';

// Riceviamo i dati inviati in formato JSON dal frontend
$input = json_decode(file_get_contents('php://input'), true);

// --- Validazione e Sicurezza dell'Input ---
if (!$input || !isset($input['sezione_id']) || !isset($input['type'])) {
    // Se i dati non sono corretti, terminiamo con un errore
    echo json_encode(['success' => false, 'error' => 'Dati non validi.']);
    exit;
}

$sezione_id = filter_var($input['sezione_id'], FILTER_VALIDATE_INT);
$type = $input['type'];

// Lista dei tipi di voto consentiti per sicurezza
$allowed_types = ['likes', 'dislikes', 'more_info'];

if (!$sezione_id || !in_array($type, $allowed_types)) {
    // Se l'ID non è un numero o il tipo di voto non è valido, terminiamo con un errore
    echo json_encode(['success' => false, 'error' => 'Parametri non validi.']);
    exit;
}

// --- Interazione con il Database ---
try {
    $pdo = connect_db();

    // Iniziamo una transazione per garantire l'integrità dei dati
    $pdo->beginTransaction();

    // Usiamo un prepared statement per aggiornare il contatore corretto in modo sicuro
    // Il nome della colonna è determinato dalla variabile $type, che abbiamo già validato
    $sql_update = "UPDATE feedback_sezioni SET {$type} = {$type} + 1 WHERE sezione_id = ?";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([$sezione_id]);

    // Ora, recuperiamo i nuovi conteggi aggiornati da restituire al frontend
    $sql_select = "SELECT likes, dislikes, more_info FROM feedback_sezioni WHERE sezione_id = ?";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->execute([$sezione_id]);
    $nuovi_conteggi = $stmt_select->fetch(PDO::FETCH_ASSOC);

    // Confermiamo la transazione
    $pdo->commit();

    // Inviamo la risposta di successo con i dati aggiornati
    echo json_encode(['success' => true, 'data' => $nuovi_conteggi]);

} catch (PDOException $e) {
    // In caso di errore del database, annulliamo la transazione e restituiamo un errore
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // Per il debug: error_log($e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Errore del server. Impossibile registrare il feedback.']);
}
?>
