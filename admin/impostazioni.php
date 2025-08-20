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

$messaggio = '';

// 4. Gestione delle Azioni (Aggiungi, Aggiorna, Cancella per tutte le tabelle)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // --- Gestione Tassi di Cambio ---
    if ($action === 'aggiorna_tasso') {
        $stmt = $pdo->prepare("UPDATE tassi_cambio SET tasso_a_chf = ? WHERE id = ?");
        $stmt->execute([$_POST['tasso_a_chf'], $_POST['id']]);
        $_SESSION['messaggio'] = ['testo' => 'Tasso di cambio aggiornato!', 'tipo' => 'successo'];
    }
    
    // --- Gestione Luoghi ---
    if ($action === 'aggiungi_luogo') {
        $stmt = $pdo->prepare("INSERT INTO luoghi (nome) VALUES (?)");
        $stmt->execute([$_POST['nome']]);
        $_SESSION['messaggio'] = ['testo' => 'Luogo aggiunto!', 'tipo' => 'successo'];
    }
    if ($action === 'aggiorna_luogo') {
        $stmt = $pdo->prepare("UPDATE luoghi SET nome = ? WHERE id = ?");
        $stmt->execute([$_POST['nome'], $_POST['id']]);
        $_SESSION['messaggio'] = ['testo' => 'Luogo aggiornato!', 'tipo' => 'successo'];
    }
    if ($action === 'cancella_luogo') {
        $stmt = $pdo->prepare("DELETE FROM luoghi WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $_SESSION['messaggio'] = ['testo' => 'Luogo cancellato!', 'tipo' => 'errore'];
    }

    // --- Gestione Iscritti ---
    if ($action === 'aggiungi_iscritto') {
        $stmt = $pdo->prepare("INSERT INTO iscritti (nome, email) VALUES (?, ?)");
        $stmt->execute([$_POST['nome'], $_POST['email']]);
        $_SESSION['messaggio'] = ['testo' => 'Iscritto aggiunto!', 'tipo' => 'successo'];
    }
    if ($action === 'aggiorna_iscritto') {
        $stmt = $pdo->prepare("UPDATE iscritti SET nome = ?, email = ? WHERE id = ?");
        $stmt->execute([$_POST['nome'], $_POST['email'], $_POST['id']]);
        $_SESSION['messaggio'] = ['testo' => 'Iscritto aggiornato!', 'tipo' => 'successo'];
    }
    if ($action === 'cancella_iscritto') {
        $stmt = $pdo->prepare("DELETE FROM iscritti WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $_SESSION['messaggio'] = ['testo' => 'Iscritto cancellato!', 'tipo' => 'errore'];
    }

    // --- Gestione Utenti Autorizzati ---
    if ($action === 'aggiungi_utente') {
        $stmt = $pdo->prepare("INSERT INTO utenti_autorizzati (email) VALUES (?)");
        $stmt->execute([$_POST['email']]);
        $_SESSION['messaggio'] = ['testo' => 'Utente autorizzato aggiunto!', 'tipo' => 'successo'];
    }
    if ($action === 'aggiorna_utente') {
        $stmt = $pdo->prepare("UPDATE utenti_autorizzati SET email = ? WHERE id = ?");
        $stmt->execute([$_POST['email'], $_POST['id']]);
        $_SESSION['messaggio'] = ['testo' => 'Utente aggiornato!', 'tipo' => 'successo'];
    }
    if ($action === 'cancella_utente') {
        $stmt = $pdo->prepare("DELETE FROM utenti_autorizzati WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $_SESSION['messaggio'] = ['testo' => 'Utente autorizzato rimosso!', 'tipo' => 'errore'];
    }

    header("Location: impostazioni.php");
    exit;
}

if (isset($_SESSION['messaggio'])) {
    $messaggio = $_SESSION['messaggio'];
    unset($_SESSION['messaggio']);
}

// 5. Estraiamo tutti i dati dalle tabelle di configurazione
$tassi = $pdo->query("SELECT * FROM tassi_cambio ORDER BY valuta ASC")->fetchAll(PDO::FETCH_ASSOC);
$luoghi = $pdo->query("SELECT * FROM luoghi ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
$iscritti = $pdo->query("SELECT * FROM iscritti ORDER BY data_iscrizione DESC")->fetchAll(PDO::FETCH_ASSOC);
$utenti = $pdo->query("SELECT * FROM utenti_autorizzati ORDER BY email ASC")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impostazioni - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/usa/assets/css/style.css">
</head>
<body class="bg-stone-100">

    <header class="bg-white text-stone-800 shadow-lg">
        <div class="main-container flex justify-between items-center h-16">
            <h1 class="text-xl font-bold">Impostazioni</h1>
            <div>
                <a href="index.php" class="text-sm font-semibold text-purple-600 hover:underline">&larr; Torna alla Dashboard</a>
            </div>
        </div>
    </header>

    <main class="main-container py-12">
        
        <?php if ($messaggio): ?>
            <div class="<?php echo $messaggio['tipo'] === 'successo' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'; ?> border px-4 py-3 rounded relative mb-4" role="alert">
                <?php echo htmlspecialchars($messaggio['testo']); ?>
            </div>
        <?php endif; ?>

        <div class="bg-white p-8 rounded-xl shadow-lg">
            <!-- Navigazione a Schede (Tabs) -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button class="tab-button active" data-target="tassi">Tassi di Cambio</button>
                    <button class="tab-button" data-target="luoghi">Luoghi</button>
                    <button class="tab-button" data-target="iscritti">Iscritti</button>
                    <button class="tab-button" data-target="utenti">Utenti Autorizzati</button>
                </nav>
            </div>

            <!-- Contenuto delle Schede -->
            <div class="mt-8">
                <!-- Scheda Tassi di Cambio -->
                <div id="tassi" class="tab-content">
                    <h3 class="text-2xl font-bold text-stone-900 mb-4">Gestisci Tassi di Cambio</h3>
                    <?php foreach ($tassi as $tasso): ?>
                        <form action="impostazioni.php" method="POST" class="flex items-center gap-4 mb-2">
                            <input type="hidden" name="id" value="<?php echo $tasso['id']; ?>">
                            <label class="font-bold text-lg text-stone-700">1 <?php echo htmlspecialchars($tasso['valuta']); ?> =</label>
                            <input type="number" step="0.000001" name="tasso_a_chf" value="<?php echo htmlspecialchars($tasso['tasso_a_chf']); ?>" class="rounded-md border-stone-300 shadow-sm">
                            <span class="font-bold text-lg text-stone-700">CHF</span>
                            <button type="submit" name="action" value="aggiorna_tasso" class="nav-button text-sm">Aggiorna</button>
                        </form>
                    <?php endforeach; ?>
                </div>

                <!-- Scheda Luoghi -->
                <div id="luoghi" class="tab-content hidden">
                    <h3 class="text-2xl font-bold text-stone-900 mb-4">Gestisci Luoghi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <ul class="divide-y divide-stone-200 border rounded-lg">
                                <?php foreach ($luoghi as $luogo): ?>
                                    <li class="p-2 flex justify-between items-center gap-2">
                                        <form action="impostazioni.php" method="POST" class="flex-grow flex items-center gap-2">
                                            <input type="hidden" name="id" value="<?php echo $luogo['id']; ?>">
                                            <input type="text" name="nome" value="<?php echo htmlspecialchars($luogo['nome']); ?>" class="w-full rounded-md border-stone-300 text-sm">
                                            <button type="submit" name="action" value="aggiorna_luogo" title="Salva" class="p-2 rounded-full hover:bg-green-100 text-green-600 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg></button>
                                        </form>
                                        <form action="impostazioni.php" method="POST" onsubmit="return confirm('Sei sicuro?');">
                                            <input type="hidden" name="id" value="<?php echo $luogo['id']; ?>">
                                            <button type="submit" name="action" value="cancella_luogo" title="Cancella" class="p-2 rounded-full hover:bg-red-100 text-red-600 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" /></svg></button>
                                        </form>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <form action="impostazioni.php" method="POST" class="space-y-4">
                            <h4 class="font-bold">Aggiungi Nuovo Luogo</h4>
                            <div>
                                <label for="nome_luogo" class="sr-only">Nome Luogo</label>
                                <input type="text" name="nome" id="nome_luogo" required class="w-full rounded-md border-stone-300 shadow-sm">
                            </div>
                            <button type="submit" name="action" value="aggiungi_luogo" class="nav-button text-sm">Aggiungi</button>
                        </form>
                    </div>
                </div>

                <!-- Scheda Iscritti -->
                <div id="iscritti" class="tab-content hidden">
                    <h3 class="text-2xl font-bold text-stone-900 mb-4">Gestisci Iscritti alle Notifiche</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <ul class="divide-y divide-stone-200 border rounded-lg">
                                <?php foreach ($iscritti as $iscritto): ?>
                                    <li class="p-2 flex justify-between items-center gap-2">
                                        <form action="impostazioni.php" method="POST" class="flex-grow flex items-center gap-2">
                                            <input type="hidden" name="id" value="<?php echo $iscritto['id']; ?>">
                                            <input type="text" name="nome" value="<?php echo htmlspecialchars($iscritto['nome']); ?>" class="w-1/3 rounded-md border-stone-300 text-sm">
                                            <input type="email" name="email" value="<?php echo htmlspecialchars($iscritto['email']); ?>" class="w-2/3 rounded-md border-stone-300 text-sm">
                                            <button type="submit" name="action" value="aggiorna_iscritto" title="Salva" class="p-2 rounded-full hover:bg-green-100 text-green-600 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg></button>
                                        </form>
                                        <form action="impostazioni.php" method="POST" onsubmit="return confirm('Sei sicuro?');">
                                            <input type="hidden" name="id" value="<?php echo $iscritto['id']; ?>">
                                            <button type="submit" name="action" value="cancella_iscritto" title="Cancella" class="p-2 rounded-full hover:bg-red-100 text-red-600 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" /></svg></button>
                                        </form>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                         <form action="impostazioni.php" method="POST" class="space-y-4">
                            <h4 class="font-bold">Aggiungi Nuovo Iscritto</h4>
                            <div>
                                <label for="nome_iscritto" class="sr-only">Nome</label>
                                <input type="text" name="nome" id="nome_iscritto" required class="w-full rounded-md border-stone-300 shadow-sm" placeholder="Nome">
                            </div>
                            <div>
                                <label for="email_iscritto" class="sr-only">Email</label>
                                <input type="email" name="email" id="email_iscritto" required class="w-full rounded-md border-stone-300 shadow-sm" placeholder="Email">
                            </div>
                            <button type="submit" name="action" value="aggiungi_iscritto" class="nav-button text-sm">Aggiungi</button>
                        </form>
                    </div>
                </div>

                <!-- Scheda Utenti Autorizzati -->
                <div id="utenti" class="tab-content hidden">
                    <h3 class="text-2xl font-bold text-stone-900 mb-4">Gestisci Utenti Autorizzati (Admin)</h3>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <ul class="divide-y divide-stone-200 border rounded-lg">
                                <?php foreach ($utenti as $utente): ?>
                                    <li class="p-2 flex justify-between items-center gap-2">
                                        <form action="impostazioni.php" method="POST" class="flex-grow flex items-center gap-2">
                                            <input type="hidden" name="id" value="<?php echo $utente['id']; ?>">
                                            <input type="email" name="email" value="<?php echo htmlspecialchars($utente['email']); ?>" class="w-full rounded-md border-stone-300 text-sm">
                                            <button type="submit" name="action" value="aggiorna_utente" title="Salva" class="p-2 rounded-full hover:bg-green-100 text-green-600 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg></button>
                                        </form>
                                        <form action="impostazioni.php" method="POST" onsubmit="return confirm('Sei sicuro?');">
                                            <input type="hidden" name="id" value="<?php echo $utente['id']; ?>">
                                            <button type="submit" name="action" value="cancella_utente" title="Cancella" class="p-2 rounded-full hover:bg-red-100 text-red-600 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" /></svg></button>
                                        </form>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <form action="impostazioni.php" method="POST" class="space-y-4">
                            <h4 class="font-bold">Aggiungi Nuovo Utente</h4>
                            <div>
                                <label for="email_utente" class="sr-only">Email Utente</label>
                                <input type="email" name="email" id="email_utente" required class="w-full rounded-md border-stone-300 shadow-sm">
                            </div>
                            <button type="submit" name="action" value="aggiungi_utente" class="nav-button text-sm">Aggiungi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab-button');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    contents.forEach(c => c.classList.add('hidden'));

                    tab.classList.add('active');
                    document.getElementById(tab.dataset.target).classList.remove('hidden');
                });
            });
        });
    </script>
    <style>
        .tab-button {
            padding: 1rem 0.5rem;
            border-bottom: 2px solid transparent;
            font-weight: 600;
            color: #78716c; /* stone-500 */
            transition: all 0.2s ease-in-out;
        }
        .tab-button:hover {
            color: #1c1917; /* stone-900 */
        }
        .tab-button.active {
            color: #7c3aed; /* purple-600 */
            border-color: #7c3aed;
        }
    </style>
</body>
</html>
