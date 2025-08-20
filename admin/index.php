<?php
// 1. Definiamo la costante di sicurezza e avviamo la sessione
define('ABSPATH', true);
session_start();

// 2. Controllo di Sicurezza.
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: /usa/admin/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Guida Viaggio USA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/usa/assets/css/style.css">
</head>
<body class="bg-stone-100">

    <header class="bg-white text-stone-800 shadow-lg">
        <div class="main-container flex justify-between items-center h-16">
            <h1 class="text-xl font-bold">Dashboard di Gestione</h1>
            <div>
                <?php if (isset($_SESSION['user_email'])): ?>
                    <span class="text-sm mr-4">Accesso come: <?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
                    <a href="logout.php" class="text-sm font-semibold text-purple-600 hover:underline">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="text-sm font-semibold text-purple-600 hover:underline">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="main-container py-12">
        <div class="space-y-8">
            <!-- Gruppo 1: Gestione Itinerario -->
            <div>
                <h2 class="text-sm font-semibold text-stone-500 uppercase tracking-wider mb-3">Itinerario</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <a href="gestisci_itinerario.php" class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow flex flex-col items-center text-center">
                        <div class="bg-purple-100 p-3 rounded-full mb-4">
                            <svg class="h-8 w-8 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v15m6-15v15m-10.875 0h15.75c.621 0 1.125-.504 1.125-1.125V5.625c0-.621-.504-1.125-1.125-1.125H4.125a1.125 1.125 0 00-1.125 1.125v12.75c0 .621.504 1.125 1.125 1.125z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-stone-900">Visualizza Itinerario</h3>
                        <p class="text-sm text-stone-600 mt-2">Consulta l'elenco dei giorni e accedi alle modifiche dei contenuti.</p>
                    </a>
                    <a href="gestisci_giorni.php" class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow flex flex-col items-center text-center">
                        <div class="bg-orange-100 p-3 rounded-full mb-4">
                            <svg class="h-8 w-8 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0h18M-4.5 12h22.5" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-stone-900">Gestione Giorni</h3>
                        <p class="text-sm text-stone-600 mt-2">Aggiungi o modifica i dati principali di ogni giorno del viaggio.</p>
                    </a>
                    <a href="../map/index.php" target="_blank" class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow flex flex-col items-center text-center">
                        <div class="bg-teal-100 p-3 rounded-full mb-4">
                            <svg class="h-8 w-8 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.869 5.186c-.748.374-1.238 1.17-1.238 1.994v10.198c0 .823.49 1.62 1.238 1.994l4.875 2.437a1.125 1.125 0 001.006 0l4.875-2.437a1.125 1.125 0 00.622-1.006v-1.934c0-.317-.112-.619-.314-.844l-2.437-2.132c-.317-.278-.82-.278-1.137 0L9.5 14.25l-2.437 2.132c-.202.177-.314.427-.314.688v1.934m8.497-9.311l2.437 2.132c.202.177.314.427.314.688v4.826a1.125 1.125 0 01-1.238 1.006l-4.875-2.437a1.125 1.125 0 01-1.006 0l-4.875 2.437A1.125 1.125 0 013 18.18V7.982c0-.824.49-1.62 1.238-1.994l4.875-2.437a1.125 1.125 0 011.006 0l4.875 2.437c.748.374 1.238 1.17 1.238 1.994z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-stone-900">Mappa del Viaggio</h3>
                        <p class="text-sm text-stone-600 mt-2">Visualizza l'itinerario completo su una mappa interattiva.</p>
                    </a>
                </div>
            </div>

            <!-- Gruppo 2: Gestione Finanziaria -->
            <div>
                <h2 class="text-sm font-semibold text-stone-500 uppercase tracking-wider mb-3">Finanze</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="budget.php" class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow flex flex-col items-center text-center">
                        <div class="bg-blue-100 p-3 rounded-full mb-4">
                            <svg class="h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.75A.75.75 0 013 4.5h.75m0 0a9 9 0 0118 0m-18 0a9 9 0 0018 0m-18 0H3.75m14.25 0H21m-12 12.75a.75.75 0 010 1.5h-2.25a.75.75 0 010-1.5h2.25z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-stone-900">Gestione Budget</h3>
                        <p class="text-sm text-stone-600 mt-2">Tieni traccia di tutte le spese, modifica gli importi e gestisci i pagamenti.</p>
                    </a>
                    <a href="analisi_budget.php" class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow flex flex-col items-center text-center">
                        <div class="bg-yellow-100 p-3 rounded-full mb-4">
                            <svg class="h-8 w-8 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 100 15 7.5 7.5 0 000-15zM21 21l-5.197-5.197" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-stone-900">Analisi Budget</h3>
                        <p class="text-sm text-stone-600 mt-2">Analizza i costi previsti, le quote individuali e lo scostamento dal budget.</p>
                    </a>
                    <a href="riepilogo_conti.php" class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow flex flex-col items-center text-center">
                        <div class="bg-green-100 p-3 rounded-full mb-4">
                            <svg class="h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0l.879-.659M7.5 14.25l.879.659c1.171.879 3.07.879 4.242 0l.879-.659M12 21a9 9 0 100-18 9 9 0 000 18z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-stone-900">Riepilogo Conti</h3>
                        <p class="text-sm text-stone-600 mt-2">Visualizza i saldi di ogni partecipante e scopri come pareggiare i conti.</p>
                    </a>
                    <a href="infografica_budget.php" class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow flex flex-col items-center text-center">
                        <div class="bg-lime-100 p-3 rounded-full mb-4">
                           <svg class="h-8 w-8 text-lime-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 100 15 7.5 7.5 0 000-15z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v9h9" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-stone-900">Infografica Budget</h3>
                        <p class="text-sm text-stone-600 mt-2">Visualizza i dati del budget con grafici interattivi.</p>
                    </a>
                </div>
            </div>

            <!-- Gruppo 3: Collaborazione e Impostazioni -->
            <div>
                <h2 class="text-sm font-semibold text-stone-500 uppercase tracking-wider mb-3">Collaborazione e Impostazioni</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <a href="feedback.php" class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow flex flex-col items-center text-center">
                        <div class="bg-pink-100 p-3 rounded-full mb-4">
                            <svg class="h-8 w-8 text-pink-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-stone-900">Riepilogo Feedback</h3>
                        <p class="text-sm text-stone-600 mt-2">Controlla i voti e le preferenze espresse dai partecipanti sul sito.</p>
                    </a>
                    <a href="invia_notifica.php" class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow flex flex-col items-center text-center">
                        <div class="bg-indigo-100 p-3 rounded-full mb-4">
                            <svg class="h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-stone-900">Invia Notifica</h3>
                        <p class="text-sm text-stone-600 mt-2">Invia un'email a tutti gli iscritti per avvisarli che ci sono novità sull'itinerario.</p>
                    </a>
                    <a href="impostazioni.php" class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow flex flex-col items-center text-center">
                        <div class="bg-gray-100 p-3 rounded-full mb-4">
                            <svg class="h-8 w-8 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.438.992a6.759 6.759 0 010 1.25c0 .379.145.752.438.992l1.003.827c.446.368.709.946.26 1.431l-1.296 2.247a1.125 1.125 0 01-1.37.49l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.063-.374-.313-.686-.645-.87a6.52 6.52 0 01-.22-.127c-.324-.196-.72-.257-1.075-.124l-1.217.456a1.125 1.125 0 01-1.37-.49l-1.296-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.437-.992a6.759 6.759 0 010-1.25c0-.379-.145-.752-.438-.992l-1.004-.827a1.125 1.125 0 01-.26-1.431l1.296-2.247a1.125 1.125 0 011.37-.49l1.217.456c.355.133.75.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-stone-900">Impostazioni</h3>
                <p class="text-sm text-stone-600 mt-2">Gestisci i tassi di cambio e altre configurazioni del portale.</p>
            </a>
        </div>
    </main>

</body>
</html>
