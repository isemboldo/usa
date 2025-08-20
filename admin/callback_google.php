<?php
// 1. Definiamo la costante di sicurezza.
define('ABSPATH', true);

// Includiamo le nostre configurazioni
require_once 'admin_config.php';
require_once '../page/config.php'; 

// Se Google ci rimanda un codice di errore, lo mostriamo
if (isset($_GET['error'])) {
    die('Errore di autenticazione Google: ' . htmlspecialchars($_GET['error']));
}

// Se non riceviamo il codice di autorizzazione, c'è un problema
if (!isset($_GET['code'])) {
    die('Errore: Codice di autorizzazione Google non ricevuto.');
}

$code = $_GET['code'];

// --- Passaggio 1: Scambiare il codice per un token di accesso ---
$token_url = 'https://oauth2.googleapis.com/token';
$token_data = [
    'code' => $code,
    'client_id' => GOOGLE_CLIENT_ID,
    'client_secret' => GOOGLE_CLIENT_SECRET,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'grant_type' => 'authorization_code'
];

$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_data));
$response = curl_exec($ch);
curl_close($ch);
$token_info = json_decode($response, true);

if (!isset($token_info['access_token'])) {
    die('Errore: Impossibile ottenere il token di accesso da Google. Risposta: ' . htmlspecialchars($response));
}

$access_token = $token_info['access_token'];

// --- Passaggio 2: Usare il token per ottenere le informazioni dell'utente ---
$user_info_url = 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json';
$ch = curl_init($user_info_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $access_token]);
$response = curl_exec($ch);
curl_close($ch);
$user_info = json_decode($response, true);

if (!isset($user_info['email'])) {
    die('Errore: Impossibile recuperare l\'indirizzo email dell\'utente da Google.');
}

$user_email = $user_info['email'];

// --- Passaggio 3: Verificare se l'utente è autorizzato nel nostro database ---
try {
    $pdo = connect_db();
    $stmt = $pdo->prepare("SELECT * FROM utenti_autorizzati WHERE email = ?");
    $stmt->execute([$user_email]);
    $authorized_user = $stmt->fetch();

    if ($authorized_user) {
        // Utente autorizzato! Creiamo la sessione sicura.
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_email'] = $user_email;
        
        // Reindirizziamo alla dashboard dell'area admin
        header('Location: /usa/admin/index.php');
        exit;
    } else {
        // Utente non autorizzato
        die('Accesso negato. L\'indirizzo email ' . htmlspecialchars($user_email) . ' non è autorizzato a gestire questo sito.');
    }

} catch (PDOException $e) {
    die("Errore del database durante la verifica dell'utente.");
}
?>
