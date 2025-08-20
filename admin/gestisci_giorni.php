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

// 4. Carichiamo i dati di supporto per i menu a tendina
$parti = $pdo->query("SELECT id, titolo FROM parti ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

$messaggio = '';

// 5. Gestione delle Azioni (Aggiungi, Aggiorna, Cancella)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'aggiungi') {
        $sql = "INSERT INTO giorni (parte_id, giorno_num, data, titolo, immagine_copertina, riassunto) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['parte_id'],
            $_POST['giorno_num'],
            $_POST['data'],
            $_POST['titolo'],
            $_POST['immagine_copertina'],
            $_POST['riassunto']
        ]);
        $_SESSION['messaggio'] = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">Giorno aggiunto con successo!</div>';
    }

    if ($action === 'aggiorna') {
        $giorno_id = filter_input(INPUT_POST, 'giorno_id', FILTER_VALIDATE_INT);
        if ($giorno_id) {
            $sql = "UPDATE giorni SET parte_id = ?, giorno_num = ?, data = ?, titolo = ?, immagine_copertina = ?, riassunto = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_POST['parte_id'],
                $_POST['giorno_num'],
                $_POST['data'],
                $_POST['titolo'],
                $_POST['immagine_copertina'],
                $_POST['riassunto'],
                $giorno_id
            ]);
            $_SESSION['messaggio'] = '<div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">Giorno aggiornato con successo!</div>';
        }
    }

    if ($action === 'cancella') {
        $giorno_id = filter_input(INPUT_POST, 'giorno_id', FILTER_VALIDATE_INT);
        if ($giorno_id) {
            $sql = "DELETE FROM giorni WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$giorno_id]);
            $_SESSION['messaggio'] = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">Giorno cancellato con successo!</div>';
        }
    }

    header("Location: gestisci_giorni.php");
    exit;
}

if (isset($_SESSION['messaggio'])) {
    $messaggio = $_SESSION['messaggio'];
    unset($_SESSION['messaggio']);
}

// 6. Estraiamo tutti i giorni dal database
$giorni = $pdo->query("SELECT * FROM giorni ORDER BY giorno_num ASC")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Giorni - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/usa/assets/css/style.css">
</head>
<body class="bg-stone-100">

    <header class="bg-white text-stone-800 shadow-lg">
        <div class="main-container flex justify-between items-center h-16">
            <h1 class="text-xl font-bold">Gestione Giorni</h1>
            <div>
                <a href="index.php" class="text-sm font-semibold text-purple-600 hover:underline">&larr; Torna alla Dashboard</a>
            </div>
        </div>
    </header>

    <main class="main-container py-12">
        
        <?php if ($messaggio) echo $messaggio; ?>

        <!-- Lista Giorni -->
        <section>
            <h2 class="text-3xl font-bold text-stone-900 mb-4">Elenco Giorni del Viaggio</h2>
            <div class="space-y-4">
                <?php if (empty($giorni)): ?>
                    <p class="bg-white p-6 rounded-xl shadow text-center text-stone-500">Nessun giorno inserito.</p>
                <?php else: ?>
                    <?php foreach ($giorni as $giorno): ?>
                        <div class="bg-white rounded-xl shadow-lg">
                            <!-- Riga Principale Visibile -->
                            <div class="p-4 flex items-center gap-4">
                                <div class="flex-shrink-0 bg-purple-100 text-purple-700 font-bold rounded-full h-12 w-12 flex items-center justify-center">
                                    <?php echo $giorno['giorno_num']; ?>
                                </div>
                                <div class="flex-grow">
                                    <p class="font-bold text-stone-800"><?php echo htmlspecialchars($giorno['titolo']); ?></p>
                                    <p class="text-sm text-stone-500"><?php echo htmlspecialchars($giorno['data']); ?></p>
                                </div>
                                <div class="flex-shrink-0">
                                    <button type="button" class="details-toggle nav-button">Modifica</button>
                                </div>
                            </div>

                            <!-- Sezione Dettagli Nascosta -->
                            <div class="details-content hidden border-t border-stone-200 p-6 bg-stone-50">
                                <form action="gestisci_giorni.php" method="POST" class="space-y-4">
                                    <input type="hidden" name="giorno_id" value="<?php echo $giorno['id']; ?>">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-stone-700">Numero Giorno</label>
                                            <input type="number" name="giorno_num" value="<?php echo htmlspecialchars($giorno['giorno_num']); ?>" class="mt-1 w-full rounded-md border-stone-300 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-stone-700">Data</label>
                                            <input type="text" name="data" value="<?php echo htmlspecialchars($giorno['data']); ?>" class="mt-1 w-full rounded-md border-stone-300 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-stone-700">Parte del Viaggio</label>
                                            <select name="parte_id" class="mt-1 w-full rounded-md border-stone-300 text-sm">
                                                <?php foreach ($parti as $parte): ?>
                                                    <option value="<?php echo $parte['id']; ?>" <?php echo ($giorno['parte_id'] == $parte['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($parte['titolo']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-stone-700">Titolo</label>
                                        <input type="text" name="titolo" value="<?php echo htmlspecialchars($giorno['titolo']); ?>" class="mt-1 w-full rounded-md border-stone-300 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-stone-700">Immagine Copertina</label>
                                        <input type="text" name="immagine_copertina" value="<?php echo htmlspecialchars($giorno['immagine_copertina']); ?>" class="mt-1 w-full rounded-md border-stone-300 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-stone-700">Riassunto</label>
                                        <textarea name="riassunto" rows="3" class="mt-1 w-full rounded-md border-stone-300 text-sm"><?php echo htmlspecialchars($giorno['riassunto']); ?></textarea>
                                    </div>
                                    <div class="pt-4 flex justify-end gap-2">
                                        <button type="submit" name="action" value="aggiorna" class="nav-button">Salva Modifiche</button>
                                        <button type="submit" name="action" value="cancella" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md text-sm" onclick="return confirm('Sei sicuro di voler cancellare questo giorno? La cancellazione rimuoverà anche tutte le sezioni di contenuto associate.');">Cancella Giorno</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- Form Aggiungi Giorno -->
        <section class="mt-12">
             <form action="gestisci_giorni.php" method="POST" class="bg-white p-8 rounded-xl shadow-lg space-y-6">
                <h2 class="text-2xl font-bold text-stone-900 border-b pb-2">Aggiungi Nuovo Giorno</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="giorno_num_add" class="block text-sm font-medium text-stone-700">Numero Giorno</label>
                        <input type="number" name="giorno_num" id="giorno_num_add" required class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="data_add" class="block text-sm font-medium text-stone-700">Data</label>
                        <input type="text" name="data" id="data_add" required placeholder="Es. Sabato 25 luglio 2026" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                    </div>
                </div>
                <div>
                    <label for="titolo_add" class="block text-sm font-medium text-stone-700">Titolo</label>
                    <input type="text" name="titolo" id="titolo_add" required class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                </div>
                 <div>
                    <label for="riassunto_add" class="block text-sm font-medium text-stone-700">Riassunto</label>
                    <textarea name="riassunto" id="riassunto_add" rows="3" required class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm"></textarea>
                </div>
                <div>
                    <label for="immagine_copertina_add" class="block text-sm font-medium text-stone-700">URL Immagine Copertina</label>
                    <input type="text" name="immagine_copertina" id="immagine_copertina_add" required placeholder="/usa/assets/images/giorno-X.jpg" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                </div>
                <div>
                    <label for="parte_id_add" class="block text-sm font-medium text-stone-700">Parte del Viaggio</label>
                    <select name="parte_id" id="parte_id_add" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                        <?php foreach ($parti as $parte): ?>
                            <option value="<?php echo $parte['id']; ?>"><?php echo htmlspecialchars($parte['titolo']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="pt-5">
                    <div class="flex justify-end">
                        <button type="submit" name="action" value="aggiungi" class="nav-button text-base">
                            Aggiungi Giorno
                        </button>
                    </div>
                </div>
             </form>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detailToggles = document.querySelectorAll('.details-toggle');
            detailToggles.forEach(button => {
                button.addEventListener('click', () => {
                    const content = button.closest('.bg-white').querySelector('.details-content');
                    content.classList.toggle('hidden');
                    button.textContent = content.classList.contains('hidden') ? 'Modifica' : 'Chiudi';
                });
            });
        });
    </script>
</body>
</html>
