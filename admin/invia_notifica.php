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

// Includiamo la libreria PHPMailer
require_once '../vendor/phpmailer/src/Exception.php';
require_once '../vendor/phpmailer/src/PHPMailer.php';
require_once '../vendor/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$messaggio = '';

// 4. Gestione dell'invio delle notifiche
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['invia_notifica'])) {
    try {
        $pdo = connect_db();
        $iscritti = $pdo->query("SELECT nome, email FROM iscritti")->fetchAll(PDO::FETCH_ASSOC);

        if (empty($iscritti)) {
            $messaggio = '<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">Nessun iscritto a cui inviare la notifica.</div>';
        } else {
            $mail = new PHPMailer(true);

            // Impostazioni del server SMTP (lette dal file di configurazione)
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USER;
            $mail->Password   = SMTP_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = SMTP_PORT;
            $mail->CharSet    = 'UTF-8';

            // Mittente
            $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);

            // Contenuto dell'email
            $mail->isHTML(true);
            $mail->Subject = 'Aggiornamento Itinerario di Viaggio USA 2026';
            $email_body = file_get_contents('email_template.html'); 

            $utenti_inviati = 0;
            foreach ($iscritti as $iscritto) {
                $mail->addAddress($iscritto['email'], $iscritto['nome']);
                
                $body_personale = str_replace('{{NOME_UTENTE}}', htmlspecialchars($iscritto['nome']), $email_body);
                $mail->Body = $body_personale;

                $mail->send();
                $mail->clearAddresses();
                $utenti_inviati++;
            }

            $messaggio = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">Notifica inviata con successo a ' . $utenti_inviati . ' iscritti!</div>';
        }
    } catch (Exception $e) {
        $messaggio = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">Errore durante l\'invio: ' . $mail->ErrorInfo . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invia Notifica - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/usa/assets/css/style.css">
</head>
<body class="bg-stone-100">

    <header class="bg-white text-stone-800 shadow-lg">
        <div class="main-container flex justify-between items-center h-16">
            <h1 class="text-xl font-bold">Invia Notifica Aggiornamenti</h1>
            <div>
                <a href="index.php" class="text-sm font-semibold text-purple-600 hover:underline">&larr; Torna alla Dashboard</a>
            </div>
        </div>
    </header>

    <main class="main-container py-12">
        
        <?php if ($messaggio) echo $messaggio; ?>

        <div class="bg-white p-8 rounded-xl shadow-lg text-center">
            <h2 class="text-3xl font-bold text-stone-900 mb-4">Conferma Invio Notifica</h2>
            <p class="text-stone-600 mb-6">Stai per inviare un'email a tutti gli iscritti per avvisarli che ci sono delle novità sull'itinerario. Gli utenti vedranno quali giorni sono stati modificati dalla loro ultima visita.</p>
            
            <form action="invia_notifica.php" method="POST">
                <button type="submit" name="invia_notifica" class="nav-button text-lg bg-indigo-600 hover:bg-indigo-700">
                    Invia Notifica a Tutti gli Iscritti
                </button>
            </form>
        </div>
    </main>

</body>
</html>
