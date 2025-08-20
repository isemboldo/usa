<?php
// 1. Definiamo la costante di sicurezza. Questo deve essere il primo passo.
define('ABSPATH', true);

// Includiamo le nostre configurazioni
require_once 'admin_config.php';
require_once '../page/config.php'; 

// Se Infomaniak ci rimanda un codice di errore, lo mostriamo in modo sicuro
if (isset($_GET['error'])) {
    $error_message = 'Errore di autenticazione: ' . htmlspecialchars($_GET['error']);
    if (isset($_GET['error_description'])) {
        $error_message .= ' - ' . htmlspecialchars($_GET['error_description']);
    }
    die($error_message);
}

// Se non riceviamo il codice di autorizzazione, c'è un problema
if (!isset($_GET['code'])) {
    die('Errore: Codice di autorizzazione non ricevuto.');
}

$code = $_GET['code'];

// --- Passaggio 1: Scambiare il codice per un token di accesso ---
$token_url = 'https://login.infomaniak.com/token';
$token_data = [
    'grant_type' => 'authorization_code',
    'client_id' => INFOMANIAK_CLIENT_ID,
    'client_secret' => INFOMANIAK_CLIENT_SECRET,
    'redirect_uri' => INFOMANIAK_REDIRECT_URI,
    'code' => $code
];

$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_data)); 
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded'
]);

$response = curl_exec($ch);
curl_close($ch);
$token_info = json_decode($response, true);

if (!isset($token_info['access_token'])) {
    die('Errore: Impossibile ottenere il token di accesso. Risposta dal server: ' . htmlspecialchars($response));
}

$access_token = $token_info['access_token'];

// --- Passaggio 2: Usare il token per ottenere l'email dell'utente ---
// CORREZIONE FINALE: Usiamo l'endpoint specifico per le email, che è più restrittivo e dovrebbe funzionare con lo scope 'email'.
$user_info_url = 'https://api.infomaniak.com/2/profile/emails';
$ch = curl_init($user_info_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $access_token]);
$response = curl_exec($ch);
curl_close($ch);
$user_info = json_decode($response, true);

// Il controllo ora cerca l'email nel primo elemento dell'array di dati restituito
if (!isset($user_info['result']) || $user_info['result'] !== 'success' || !isset($user_info['data'][0]['email'])) {
    die('Errore: Impossibile recuperare le informazioni dell\'utente. Risposta dal server: ' . htmlspecialchars($response));
}

// Prendiamo la prima email dalla lista
$user_email = $user_info['data'][0]['email'];

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
