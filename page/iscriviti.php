<?php
// 1. Definiamo la costante di sicurezza e avviamo la sessione.
define('ABSPATH', true);
session_start();

// 2. Includiamo la configurazione e ci connettiamo al database.
require_once 'config.php';

// 3. Verifichiamo che la richiesta sia di tipo POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /usa/index.php');
    exit;
}

// 4. Validiamo i dati ricevuti dal modulo.
$nome = trim($_POST['nome'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);

// Definiamo la pagina da cui l'utente proviene per il reindirizzamento.
$redirect_url = $_POST['redirect_url'] ?? '/usa/index.php';

if (empty($nome) || !$email) {
    $_SESSION['messaggio_iscrizione'] = ['tipo' => 'errore', 'testo' => 'Per favore, inserisci un nome e un\'email valida.'];
    header('Location: ' . $redirect_url);
    exit;
}

// 5. Inseriamo i dati nel database in modo sicuro.
try {
    $pdo = connect_db();
    $sql = "INSERT INTO iscritti (nome, email) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $email]);

    $_SESSION['messaggio_iscrizione'] = ['tipo' => 'successo', 'testo' => 'Grazie! La tua iscrizione è stata registrata con successo.'];

} catch (PDOException $e) {
    // Gestiamo l'errore di email duplicata (codice 23000)
    if ($e->getCode() == 23000) {
        $_SESSION['messaggio_iscrizione'] = ['tipo' => 'errore', 'testo' => 'Questa email è già iscritta.'];
    } else {
        $_SESSION['messaggio_iscrizione'] = ['tipo' => 'errore', 'testo' => 'Si è verificato un errore. Riprova più tardi.'];
        // Per il debug: error_log($e->getMessage());
    }
}

// 6. Reindirizziamo l'utente alla pagina di provenienza.
header('Location: ' . $redirect_url);
exit;
?>
