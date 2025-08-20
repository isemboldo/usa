<?php
require_once 'admin_config.php';

// Costruisce l'URL per Infomaniak
$infomaniak_auth_url = 'https://login.infomaniak.com/authorize?' . http_build_query([
    'client_id' => INFOMANIAK_CLIENT_ID,
    'redirect_uri' => INFOMANIAK_REDIRECT_URI,
    'response_type' => 'code',
    'scope' => 'openid profile email' // Manteniamo gli scope corretti
]);

// Costruisce l'URL per Google
$google_auth_url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
    'client_id' => GOOGLE_CLIENT_ID,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'response_type' => 'code',
    'scope' => 'openid email profile' // Scope standard per Google
]);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accesso Area Amministrazione</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/usa/assets/css/style.css">
</head>
<body class="bg-stone-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-xl shadow-lg">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-stone-900">Accesso Sicuro</h2>
            <p class="mt-2 text-stone-600">Scegli un metodo per accedere al portale di gestione.</p>
        </div>
        <div class="space-y-4">
            <a href="<?php echo htmlspecialchars($google_auth_url); ?>" class="w-full flex items-center justify-center nav-button text-lg bg-blue-600 hover:bg-blue-700">
                <!-- Google Icon SVG -->
                <svg class="w-6 h-6 mr-3" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path><path fill="none" d="M0 0h48v48H0z"></path></svg>
                Accedi con Google
            </a>
            <a href="<?php echo htmlspecialchars($infomaniak_auth_url); ?>" class="w-full flex items-center justify-center nav-button text-lg bg-gray-700 hover:bg-gray-800">
                Accedi con Infomaniak
            </a>
        </div>
    </div>
</body>
</html>
