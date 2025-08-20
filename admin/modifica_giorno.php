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

// 5. Otteniamo l'ID del giorno dall'URL (sia da GET che da POST per le azioni)
$giorno_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'giorno_id', FILTER_VALIDATE_INT);
if (!$giorno_id) {
    die("ID del giorno non valido.");
}

// 6. Gestione delle Azioni (Salvataggio, Aggiunta o Cancellazione Sezione)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $modifica_effettuata = false;

    if ($action === 'salva_modifiche') {
        $sql_giorno = "UPDATE giorni SET parte_id = ?, giorno_num = ?, data = ?, titolo = ?, immagine_copertina = ?, riassunto = ? WHERE id = ?";
        $stmt_giorno = $pdo->prepare($sql_giorno);
        $stmt_giorno->execute([
            $_POST['parte_id'], $_POST['giorno_num'], $_POST['data'],
            $_POST['titolo'], $_POST['immagine_copertina'], $_POST['riassunto'],
            $giorno_id
        ]);

        if (isset($_POST['sezioni'])) {
            $sql_sezione = "UPDATE sezioni SET sovratitolo = ?, titolo = ?, testo = ?, immagine = ?, ordine = ? WHERE id = ? AND giorno_id = ?";
            $stmt_sezione = $pdo->prepare($sql_sezione);
            foreach ($_POST['sezioni'] as $sezione_id => $dati_sezione) {
                $stmt_sezione->execute([
                    $dati_sezione['sovratitolo'], $dati_sezione['titolo'], $dati_sezione['testo'],
                    $dati_sezione['immagine'], $dati_sezione['ordine'], $sezione_id, $giorno_id
                ]);
            }
        }
        $_SESSION['messaggio'] = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">Modifiche salvate con successo!</div>';
        $modifica_effettuata = true;
    }

    if ($action === 'aggiungi_sezione') {
        $stmt_max_ordine = $pdo->prepare("SELECT MAX(ordine) AS max_ordine FROM sezioni WHERE giorno_id = ?");
        $stmt_max_ordine->execute([$giorno_id]);
        $max_ordine_result = $stmt_max_ordine->fetch(PDO::FETCH_ASSOC);
        $nuovo_ordine = ($max_ordine_result && $max_ordine_result['max_ordine'] !== null) ? $max_ordine_result['max_ordine'] + 1 : 1;

        $sql_insert = "INSERT INTO sezioni (giorno_id, ordine, sovratitolo, titolo, testo, immagine) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([$giorno_id, $nuovo_ordine, 'Nuovo Sovratitolo', 'Nuova Sezione', '<p>Testo...</p>', '/usa/assets/images/placeholder.jpg']);
        
        $_SESSION['messaggio'] = '<div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">Nuova sezione aggiunta!</div>';
        $modifica_effettuata = true;
    }

    if ($action === 'cancella_sezione') {
        $sezione_id_da_cancellare = filter_input(INPUT_POST, 'sezione_id', FILTER_VALIDATE_INT);
        if ($sezione_id_da_cancellare) {
            $sql_delete = "DELETE FROM sezioni WHERE id = ? AND giorno_id = ?";
            $stmt_delete = $pdo->prepare($sql_delete);
            $stmt_delete->execute([$sezione_id_da_cancellare, $giorno_id]);
            $_SESSION['messaggio'] = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">Sezione cancellata con successo!</div>';
            $modifica_effettuata = true;
        }
    }

    // Se è stata fatta una modifica, aggiorniamo il timestamp della tabella 'giorni'
    if ($modifica_effettuata) {
        $stmt_touch = $pdo->prepare("UPDATE giorni SET data_modifica = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt_touch->execute([$giorno_id]);
    }

    header("Location: modifica_giorno.php?id=" . $giorno_id);
    exit;
}

// Mostra il messaggio di successo se presente nella sessione
if (isset($_SESSION['messaggio'])) {
    $messaggio = $_SESSION['messaggio'];
    unset($_SESSION['messaggio']);
}

// 7. Estraiamo i dati aggiornati dal database per mostrarli nel modulo.
$stmt_giorno = $pdo->prepare("SELECT * FROM giorni WHERE id = ?");
$stmt_giorno->execute([$giorno_id]);
$giorno = $stmt_giorno->fetch(PDO::FETCH_ASSOC);

if (!$giorno) {
    die("Giorno non trovato.");
}

// 8. Estraiamo le sezioni del giorno
$stmt_sezioni = $pdo->prepare("SELECT * FROM sezioni WHERE giorno_id = ? ORDER BY ordine ASC");
$stmt_sezioni->execute([$giorno_id]);
$sezioni = $stmt_sezioni->fetchAll(PDO::FETCH_ASSOC);

// 9. Troviamo l'ID del giorno precedente e successivo per la navigazione
$stmt_prev = $pdo->prepare("SELECT id FROM giorni WHERE giorno_num < ? ORDER BY giorno_num DESC LIMIT 1");
$stmt_prev->execute([$giorno['giorno_num']]);
$giorno_precedente_id = $stmt_prev->fetchColumn();

$stmt_next = $pdo->prepare("SELECT id FROM giorni WHERE giorno_num > ? ORDER BY giorno_num ASC LIMIT 1");
$stmt_next->execute([$giorno['giorno_num']]);
$giorno_successivo_id = $stmt_next->fetchColumn();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Giorno <?php echo $giorno['giorno_num']; ?> - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/usa/assets/css/style.css">
    <!-- Integrazione di CKEditor 5 (gratuito e open-source) -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
</head>
<body class="bg-stone-100">

    <header class="bg-white text-stone-800 shadow-lg">
        <div class="main-container flex justify-between items-center h-16">
            <h1 class="text-xl font-bold">Modifica Giorno</h1>
            <div>
                <a href="gestisci_itinerario.php" class="text-sm font-semibold text-purple-600 hover:underline">&larr; Torna all'Itinerario</a>
            </div>
        </div>
    </header>

    <main class="main-container py-12 pb-24">
        
        <?php if ($messaggio) echo $messaggio; ?>

        <form action="modifica_giorno.php?id=<?php echo $giorno_id; ?>" method="POST" id="edit-form">
            <input type="hidden" name="giorno_id" value="<?php echo $giorno_id; ?>">
            <div class="bg-white p-8 rounded-xl shadow-lg space-y-6">
                
                <div>
                    <h2 class="text-2xl font-bold text-stone-900 border-b pb-2 mb-4">Informazioni Generali (Giorno <?php echo $giorno['giorno_num']; ?>)</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="giorno_num" class="block text-sm font-medium text-stone-700">Numero Giorno</label>
                            <input type="number" name="giorno_num" id="giorno_num" value="<?php echo htmlspecialchars($giorno['giorno_num']); ?>" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm">
                        </div>
                        <div class="col-span-2">
                            <label for="data" class="block text-sm font-medium text-stone-700">Data</label>
                            <input type="text" name="data" id="data" value="<?php echo htmlspecialchars($giorno['data']); ?>" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="titolo" class="block text-sm font-medium text-stone-700">Titolo del Giorno</label>
                        <input type="text" name="titolo" id="titolo" value="<?php echo htmlspecialchars($giorno['titolo']); ?>" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm">
                    </div>
                    <div class="mt-4">
                        <label for="immagine_copertina" class="block text-sm font-medium text-stone-700">URL Immagine di Copertina</label>
                        <input type="text" name="immagine_copertina" id="immagine_copertina" value="<?php echo htmlspecialchars($giorno['immagine_copertina']); ?>" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm">
                    </div>
                    <div class="mt-4">
                        <label for="riassunto" class="block text-sm font-medium text-stone-700">Riassunto</label>
                        <textarea name="riassunto" id="riassunto" rows="3" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm"><?php echo htmlspecialchars($giorno['riassunto']); ?></textarea>
                    </div>
                     <div class="mt-4">
                        <label for="parte_id" class="block text-sm font-medium text-stone-700">Parte del Viaggio</label>
                        <select name="parte_id" id="parte_id" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm">
                            <?php foreach ($parti as $parte): ?>
                                <option value="<?php echo $parte['id']; ?>" <?php echo ($giorno['parte_id'] == $parte['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($parte['titolo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-stone-900 border-b pb-2 mb-4">Contenuto Dettagliato</h2>
                    <div class="space-y-4">
                        <?php foreach ($sezioni as $sezione): ?>
                            <div class="border border-stone-200 rounded-lg bg-white">
                                <!-- Riga Principale Visibile -->
                                <div class="p-4 flex items-center gap-4 cursor-pointer details-toggle">
                                    <div class="flex-shrink-0 text-purple-600 font-bold">
                                        #<?php echo $sezione['id']; ?>
                                    </div>
                                    <div class="flex-grow">
                                        <p class="font-bold text-stone-800"><?php echo htmlspecialchars($sezione['titolo']); ?></p>
                                        <p class="text-sm text-stone-500"><?php echo htmlspecialchars($sezione['sovratitolo']); ?></p>
                                    </div>
                                    <div class="flex-shrink-0 text-sm font-semibold text-purple-600">
                                        <span class="arrow">▼</span> Modifica
                                    </div>
                                </div>
                                <!-- Sezione Dettagli Nascosta -->
                                <div class="details-content hidden border-t border-stone-200 p-4 bg-stone-50">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="sezione_<?php echo $sezione['id']; ?>_sovratitolo" class="block text-sm font-medium text-stone-700">Sovratitolo (Occhiello)</label>
                                            <input type="text" name="sezioni[<?php echo $sezione['id']; ?>][sovratitolo]" id="sezione_<?php echo $sezione['id']; ?>_sovratitolo" value="<?php echo htmlspecialchars($sezione['sovratitolo']); ?>" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm">
                                        </div>
                                        <div>
                                            <label for="sezione_<?php echo $sezione['id']; ?>_titolo" class="block text-sm font-medium text-stone-700">Titolo Sezione</label>
                                            <input type="text" name="sezioni[<?php echo $sezione['id']; ?>][titolo]" id="sezione_<?php echo $sezione['id']; ?>_titolo" value="<?php echo htmlspecialchars($sezione['titolo']); ?>" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm">
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="sezione_<?php echo $sezione['id']; ?>_immagine" class="block text-sm font-medium text-stone-700">URL Immagine Sezione</label>
                                        <input type="text" name="sezioni[<?php echo $sezione['id']; ?>][immagine]" id="sezione_<?php echo $sezione['id']; ?>_immagine" value="<?php echo htmlspecialchars($sezione['immagine']); ?>" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="sezione_<?php echo $sezione['id']; ?>_testo" class="block text-sm font-medium text-stone-700">Testo Dettagliato</label>
                                        <textarea name="sezioni[<?php echo $sezione['id']; ?>][testo]" id="sezione_<?php echo $sezione['id']; ?>_testo" class="wysiwyg mt-1 block w-full rounded-md border-stone-300 shadow-sm"><?php echo htmlspecialchars($sezione['testo']); ?></textarea>
                                    </div>
                                    <div class="mt-4 pt-4 border-t flex justify-between items-center">
                                         <div>
                                            <label for="sezione_<?php echo $sezione['id']; ?>_ordine" class="block text-sm font-medium text-stone-700">Ordine</label>
                                            <input type="number" name="sezioni[<?php echo $sezione['id']; ?>][ordine]" id="sezione_<?php echo $sezione['id']; ?>_ordine" value="<?php echo htmlspecialchars($sezione['ordine']); ?>" class="mt-1 block w-20 rounded-md border-stone-300 shadow-sm">
                                        </div>
                                        <form action="modifica_giorno.php" method="POST" onsubmit="return confirm('Sei sicuro di voler cancellare questa sezione?');">
                                            <input type="hidden" name="giorno_id" value="<?php echo $giorno_id; ?>">
                                            <input type="hidden" name="sezione_id" value="<?php echo $sezione['id']; ?>">
                                            <input type="hidden" name="action" value="cancella_sezione">
                                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-md text-sm font-semibold hover:bg-red-600 transition-colors">&times; Cancella Sezione</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </form>

        <div class="mt-8">
            <form action="modifica_giorno.php" method="POST">
                <input type="hidden" name="giorno_id" value="<?php echo $giorno_id; ?>">
                <button type="submit" name="action" value="aggiungi_sezione" class="w-full text-center px-6 py-3 border-2 border-dashed border-purple-300 text-purple-600 font-semibold rounded-lg hover:bg-purple-50 hover:border-purple-400 transition-colors">
                    + Aggiungi Nuova Sezione
                </button>
            </form>
        </div>
    </main>
    
    <footer class="bg-white border-t border-stone-200 py-3 fixed bottom-0 w-full">
        <div class="main-container flex justify-between items-center">
            <span class="text-sm text-stone-600 hidden sm:block">Stai modificando il Giorno <?php echo $giorno['giorno_num']; ?></span>
            <div class="flex items-center gap-2">
                <a href="/usa/page/day.php?giorno=<?php echo $giorno['giorno_num']; ?>" target="_blank" class="nav-button text-sm">Vedi Pagina Pubblica ↗</a>
                <?php if ($giorno_precedente_id): ?>
                    <a href="modifica_giorno.php?id=<?php echo $giorno_precedente_id; ?>" class="nav-button text-sm">&larr; Giorno Prec.</a>
                <?php endif; ?>
                <?php if ($giorno_successivo_id): ?>
                    <a href="modifica_giorno.php?id=<?php echo $giorno_successivo_id; ?>" class="nav-button text-sm">Giorno Succ. &rarr;</a>
                <?php endif; ?>
                <button type="submit" name="action" value="salva_modifiche" form="edit-form" class="nav-button text-sm bg-green-600 hover:bg-green-700">Salva Modifiche</button>
            </div>
        </div>
    </footer>

    <script>
        // Script per inizializzare CKEditor
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('textarea.wysiwyg').forEach(el => {
                ClassicEditor
                    .create(el, {
                        toolbar: [ 'bold', 'italic', 'underline', '|', 'bulletedList', 'numberedList', '|', 'link', 'undo', 'redo' ]
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });

            // Script per le card espandibili
            const detailToggles = document.querySelectorAll('.details-toggle');
            detailToggles.forEach(button => {
                button.addEventListener('click', () => {
                    const content = button.closest('.bg-white').querySelector('.details-content');
                    const arrow = button.querySelector('.arrow');
                    content.classList.toggle('hidden');
                    if (arrow) {
                        arrow.textContent = content.classList.contains('hidden') ? '▼' : '▲';
                    }
                });
            });
        });
    </script>
</body>
</html>
