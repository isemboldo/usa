<?php
// Impedisce l'accesso diretto al file se non viene definito ABSPATH
if ( ! defined( 'ABSPATH' ) ) {
	die('Accesso non autorizzato.');
}

// Avvia la sessione PHP per memorizzare lo stato del login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- Impostazioni di Connessione al Database ---
define('DB_HOST', '[redacted]');
define('DB_PORT', ':[redacted]');
define('DB_NAME', '[redacted]');
define('DB_USER', '[redacted]');
define('DB_PASSWORD', '[redacted]');
define('DB_CHARSET', 'utf8mb4');

// --- Impostazioni per l'Autenticazione Google ---
define('GOOGLE_CLIENT_ID', '[redacted]');
define('GOOGLE_CLIENT_SECRET', '[redacted]');
define('GOOGLE_REDIRECT_URI', 'https://isohome.ch/usa/admin/callback_google.php');

// --- Impostazioni per Server di Posta (SMTP) ---
define('SMTP_HOST', 'mail.infomaniak.com');
define('SMTP_USER', '[redacted]');
define('SMTP_PASS', '[redacted]');
define('SMTP_PORT', 465);
define('SMTP_FROM_EMAIL', 'no-reply@isohome.ch');
define('SMTP_FROM_NAME', 'Guida Viaggio USA 2026');

// --- Funzione di Connessione ---
function connect_db() {
    try {
        $dsn = "mysql:host=" . DB_HOST . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
        return $pdo;
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}
?>
