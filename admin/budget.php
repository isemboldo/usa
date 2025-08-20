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
$luoghi = $pdo->query("SELECT id, nome FROM luoghi ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
// CORREZIONE: Aggiunto il campo 'data' alla query
$giorni_select = $pdo->query("SELECT id, giorno_num, data, titolo FROM giorni ORDER BY giorno_num ASC")->fetchAll(PDO::FETCH_ASSOC);
$partecipanti = ['Federico', 'Paola', 'Elanor', 'Francesca', 'Emilio', 'Lucrezia'];
$metodi_pagamento = ['Carta di Credito', 'Contanti', 'Bonifico', 'Altro'];
$categorie = ['Voli','Alloggi','Pasti','Attrazioni','Trasporti','Varie'];


$messaggio = '';

// 5. Gestione delle Azioni (Aggiungi, Aggiorna, Cancella)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    $diviso_per_stringa = isset($_POST['diviso_per']) && is_array($_POST['diviso_per']) ? implode(', ', $_POST['diviso_per']) : '';

    if ($action === 'aggiungi') {
        $sql = "INSERT INTO spese (giorno_id, descrizione, categoria, luogo_id, importo_stimato, importo_preventivo, importo_reale, valuta, data_spesa, pagato_da, diviso_per, metodo_pagamento, note) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['giorno_id'] ?: null, $_POST['descrizione'], $_POST['categoria'], $_POST['luogo_id'] ?: null,
            $_POST['importo_stimato'] ?: 0.00, $_POST['importo_preventivo'] ?: 0.00, $_POST['importo_reale'] ?: 0.00,
            $_POST['valuta'], $_POST['data_spesa'] ?: null, $_POST['pagato_da'],
            $diviso_per_stringa, $_POST['metodo_pagamento'] ?: null, $_POST['note']
        ]);
        $_SESSION['messaggio'] = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">Spesa aggiunta con successo!</div>';
    }

    if ($action === 'aggiorna') {
        $spesa_id = filter_input(INPUT_POST, 'spesa_id', FILTER_VALIDATE_INT);
        if ($spesa_id) {
            $sql = "UPDATE spese SET giorno_id = ?, descrizione = ?, categoria = ?, luogo_id = ?, importo_stimato = ?, importo_preventivo = ?, importo_reale = ?, valuta = ?, data_spesa = ?, pagato_da = ?, diviso_per = ?, metodo_pagamento = ?, note = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_POST['giorno_id'] ?: null, $_POST['descrizione'], $_POST['categoria'], $_POST['luogo_id'] ?: null,
                $_POST['importo_stimato'] ?: 0.00, $_POST['importo_preventivo'] ?: 0.00, $_POST['importo_reale'] ?: 0.00,
                $_POST['valuta'], $_POST['data_spesa'] ?: null, $_POST['pagato_da'],
                $diviso_per_stringa, $_POST['metodo_pagamento'] ?: null, $_POST['note'], $spesa_id
            ]);
            $_SESSION['messaggio'] = '<div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">Spesa aggiornata con successo!</div>';
        }
    }

    if ($action === 'cancella') {
        $spesa_id = filter_input(INPUT_POST, 'spesa_id', FILTER_VALIDATE_INT);
        if ($spesa_id) {
            $sql = "DELETE FROM spese WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$spesa_id]);
            $_SESSION['messaggio'] = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">Spesa cancellata con successo!</div>';
        }
    }

    header("Location: budget.php");
    exit;
}

if (isset($_SESSION['messaggio'])) {
    $messaggio = $_SESSION['messaggio'];
    unset($_SESSION['messaggio']);
}

// 6. Logica di Paginazione
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 15;
$offset = ($page - 1) * $per_page;

$total_spese_stmt = $pdo->query("SELECT COUNT(*) FROM spese");
$total_spese = $total_spese_stmt->fetchColumn();
$total_pages = ceil($total_spese / $per_page);

// 7. Estraiamo le spese per la pagina corrente e i totali
$spese_stmt = $pdo->prepare("SELECT * FROM spese ORDER BY data_spesa DESC, id DESC LIMIT :limit OFFSET :offset");
$spese_stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$spese_stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$spese_stmt->execute();
$spese = $spese_stmt->fetchAll(PDO::FETCH_ASSOC);

$totali_stmt = $pdo->query("
    SELECT 
        (SELECT SUM(importo_stimato) FROM spese) AS totale_stimato,
        (SELECT SUM(importo_preventivo) FROM spese) AS totale_preventivo,
        (SELECT SUM(importo_reale) FROM spese) AS totale_reale
");
$totali = $totali_stmt->fetch(PDO::FETCH_ASSOC);

$costo_attuale_previsto = 0;
$all_spese = $pdo->query("SELECT importo_stimato, importo_preventivo, importo_reale FROM spese")->fetchAll(PDO::FETCH_ASSOC);
foreach ($all_spese as $spesa) {
    if (floatval($spesa['importo_reale']) > 0) {
        $costo_attuale_previsto += floatval($spesa['importo_reale']);
    } elseif (floatval($spesa['importo_preventivo']) > 0) {
        $costo_attuale_previsto += floatval($spesa['importo_preventivo']);
    } else {
        $costo_attuale_previsto += floatval($spesa['importo_stimato']);
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Budget - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/usa/assets/css/style.css">
</head>
<body class="bg-stone-100">

    <header class="bg-white text-stone-800 shadow-lg">
        <div class="main-container flex justify-between items-center h-16">
            <h1 class="text-xl font-bold">Gestione Budget</h1>
            <div>
                <a href="index.php" class="text-sm font-semibold text-purple-600 hover:underline">&larr; Torna alla Dashboard</a>
            </div>
        </div>
    </header>

    <main class="main-container py-12">
        
        <?php if ($messaggio) echo $messaggio; ?>

        <section class="mb-8">
            <h2 class="text-3xl font-bold text-stone-900 mb-4">Riepilogo Generale</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-lg text-center">
                    <h3 class="text-sm font-semibold text-stone-500 uppercase tracking-wider">Budget Stimato Iniziale</h3>
                    <p class="text-4xl font-bold text-stone-800 mt-2"><?php echo number_format($totali['totale_stimato'] ?? 0, 2, ',', "'"); ?> CHF</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg text-center border-2 border-purple-500">
                    <h3 class="text-sm font-semibold text-purple-700 uppercase tracking-wider">Costo Attuale Previsto</h3>
                    <p class="text-4xl font-bold text-purple-600 mt-2"><?php echo number_format($costo_attuale_previsto, 2, ',', "'"); ?> CHF</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg text-center">
                    <h3 class="text-sm font-semibold text-stone-500 uppercase tracking-wider">Spesa Reale (a consuntivo)</h3>
                    <p class="text-4xl font-bold text-green-600 mt-2"><?php echo number_format($totali['totale_reale'] ?? 0, 2, ',', "'"); ?> CHF</p>
                </div>
            </div>
        </section>

        <!-- Lista Spese -->
        <section>
            <h2 class="text-3xl font-bold text-stone-900 mb-4">Dettaglio Spese</h2>
            <div class="space-y-3">
                <?php if (empty($spese)): ?>
                    <p class="bg-white p-6 rounded-xl shadow text-center text-stone-500">Nessuna spesa inserita.</p>
                <?php else: ?>
                    <?php foreach ($spese as $spesa): ?>
                        <div class="bg-white rounded-xl shadow-lg">
                            <form action="budget.php" method="POST">
                                <input type="hidden" name="spesa_id" value="<?php echo $spesa['id']; ?>">
                                
                                <div class="p-3 flex items-center gap-4">
                                    <div class="flex-grow">
                                        <p class="font-bold text-stone-800"><?php echo htmlspecialchars($spesa['descrizione']); ?></p>
                                        <p class="text-xs text-stone-500"><?php echo htmlspecialchars($spesa['categoria']); ?> - Pagato da: <?php echo htmlspecialchars($spesa['pagato_da']); ?></p>
                                    </div>
                                    <div class="flex-shrink-0 text-right">
                                        <p class="text-xl font-bold text-green-600"><?php echo number_format($spesa['importo_reale'], 2, ',', "'"); ?> <?php echo htmlspecialchars($spesa['valuta']); ?></p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <button type="button" class="details-toggle text-sm font-semibold text-purple-600 hover:underline p-2">Dettagli</button>
                                    </div>
                                </div>

                                <div class="details-content hidden border-t border-stone-200 p-4 bg-stone-50">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <!-- Colonna 1 -->
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-xs font-medium text-stone-500">Descrizione</label>
                                                <input type="text" name="descrizione" value="<?php echo htmlspecialchars($spesa['descrizione']); ?>" class="w-full rounded-md border-stone-300 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-stone-500">Categoria</label>
                                                <select name="categoria" class="w-full rounded-md border-stone-300 text-sm">
                                                    <?php foreach($categorie as $cat): ?>
                                                        <option <?php echo ($spesa['categoria'] == $cat) ? 'selected' : ''; ?>><?php echo $cat; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-stone-500">Note</label>
                                                <textarea name="note" rows="2" class="w-full rounded-md border-stone-300 text-sm"><?php echo htmlspecialchars($spesa['note']); ?></textarea>
                                            </div>
                                        </div>
                                        <!-- Colonna 2 -->
                                        <div class="space-y-4">
                                             <div>
                                                <label class="block text-xs font-medium text-stone-500">Importi (Stimato / Preventivo / Reale)</label>
                                                <div class="flex gap-2">
                                                    <input type="number" step="0.01" name="importo_stimato" value="<?php echo htmlspecialchars($spesa['importo_stimato']); ?>" class="w-full rounded-md border-stone-300 text-sm text-right" placeholder="Stimato">
                                                    <input type="number" step="0.01" name="importo_preventivo" value="<?php echo htmlspecialchars($spesa['importo_preventivo']); ?>" class="w-full rounded-md border-stone-300 text-sm text-right" placeholder="Preventivo">
                                                    <input type="number" step="0.01" name="importo_reale" value="<?php echo htmlspecialchars($spesa['importo_reale']); ?>" class="w-full rounded-md border-stone-300 text-sm text-right" placeholder="Reale">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-stone-500">Data e Valuta</label>
                                                <div class="flex gap-2">
                                                    <input type="date" name="data_spesa" value="<?php echo htmlspecialchars($spesa['data_spesa']); ?>" class="w-full rounded-md border-stone-300 text-sm">
                                                    <select name="valuta" class="rounded-md border-stone-300 text-sm">
                                                        <option <?php echo ($spesa['valuta'] == 'CHF') ? 'selected' : ''; ?>>CHF</option>
                                                        <option <?php echo ($spesa['valuta'] == 'USD') ? 'selected' : ''; ?>>USD</option>
                                                        <option <?php echo ($spesa['valuta'] == 'EUR') ? 'selected' : ''; ?>>EUR</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-stone-500">Giorno Associato</label>
                                                <select name="giorno_id" class="w-full rounded-md border-stone-300 text-sm">
                                                    <option value="">-- Nessuno --</option>
                                                    <?php foreach ($giorni_select as $giorno): ?>
                                                        <option value="<?php echo $giorno['id']; ?>" <?php echo ($spesa['giorno_id'] == $giorno['id']) ? 'selected' : ''; ?>>G<?php echo $giorno['giorno_num']; ?>: <?php echo htmlspecialchars($giorno['data']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Colonna 3 -->
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-xs font-medium text-stone-500">Luogo</label>
                                                <select name="luogo_id" class="w-full rounded-md border-stone-300 text-sm">
                                                    <option value="">-- Nessuno --</option>
                                                    <?php foreach ($luoghi as $luogo): ?>
                                                        <option value="<?php echo $luogo['id']; ?>" <?php echo ($spesa['luogo_id'] == $luogo['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($luogo['nome']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-stone-500">Pagato Da / Metodo</label>
                                                <div class="flex gap-2">
                                                    <select name="pagato_da" class="w-full rounded-md border-stone-300 text-sm">
                                                        <option value="">-- Seleziona --</option>
                                                        <?php foreach ($partecipanti as $p): ?>
                                                            <option value="<?php echo $p; ?>" <?php echo ($spesa['pagato_da'] == $p) ? 'selected' : ''; ?>><?php echo $p; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <select name="metodo_pagamento" class="w-full rounded-md border-stone-300 text-sm">
                                                        <option value="">-- Nessuno --</option>
                                                        <?php foreach ($metodi_pagamento as $metodo): ?>
                                                            <option value="<?php echo $metodo; ?>" <?php echo ($spesa['metodo_pagamento'] == $metodo) ? 'selected' : ''; ?>><?php echo $metodo; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-stone-500">Diviso Per</label>
                                                <div class="grid grid-cols-2 gap-1 text-xs mt-1">
                                                    <?php $diviso_per_spesa = explode(', ', $spesa['diviso_per']); ?>
                                                    <?php foreach ($partecipanti as $p): ?>
                                                        <label class="flex items-center space-x-1 p-1 rounded hover:bg-stone-100">
                                                            <input type="checkbox" name="diviso_per[]" value="<?php echo $p; ?>" <?php echo in_array($p, $diviso_per_spesa) ? 'checked' : ''; ?> class="h-4 w-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                                            <span><?php echo $p; ?></span>
                                                        </label>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-4 border-t flex justify-end gap-2">
                                        <button type="submit" name="action" value="aggiorna" title="Aggiorna" class="p-2 rounded-full hover:bg-green-100 text-green-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        </button>
                                        <button type="submit" name="action" value="cancella" title="Cancella" class="p-2 rounded-full hover:bg-red-100 text-red-600 transition-colors" onclick="return confirm('Sei sicuro di voler cancellare questa spesa?');">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <!-- Controlli di Paginazione -->
            <div class="mt-6 flex justify-center items-center space-x-2">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>" class="nav-button">&larr; Prec.</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'bg-purple-600 text-white' : 'bg-white text-stone-700'; ?> px-4 py-2 rounded-md text-sm font-semibold hover:bg-purple-500 hover:text-white"><?php echo $i; ?></a>
                <?php endfor; ?>
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>" class="nav-button">Succ. &rarr;</a>
                <?php endif; ?>
            </div>
        </section>

        <!-- Form Aggiungi Spesa -->
        <section class="mt-12">
             <form action="budget.php" method="POST" class="bg-white p-8 rounded-xl shadow-lg space-y-6">
                <h2 class="text-2xl font-bold text-stone-900 border-b pb-2">Aggiungi Nuova Spesa</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="descrizione_add" class="block text-sm font-medium text-stone-700">Descrizione</label>
                        <input type="text" name="descrizione" id="descrizione_add" required class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="categoria_add" class="block text-sm font-medium text-stone-700">Categoria</label>
                        <select name="categoria" id="categoria_add" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                            <?php foreach($categorie as $cat): ?>
                                <option><?php echo $cat; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="importo_stimato_add" class="block text-sm font-medium text-stone-700">Importo Stimato</label>
                        <input type="number" step="0.01" name="importo_stimato" id="importo_stimato_add" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="importo_preventivo_add" class="block text-sm font-medium text-stone-700">Importo Preventivo</label>
                        <input type="number" step="0.01" name="importo_preventivo" id="importo_preventivo_add" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="importo_reale_add" class="block text-sm font-medium text-stone-700">Importo Reale</label>
                        <input type="number" step="0.01" name="importo_reale" id="importo_reale_add" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="valuta_add" class="block text-sm font-medium text-stone-700">Valuta</label>
                        <select name="valuta" id="valuta_add" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                            <option>CHF</option><option>USD</option><option>EUR</option>
                        </select>
                    </div>
                    <div>
                        <label for="data_spesa_add" class="block text-sm font-medium text-stone-700">Data Spesa</label>
                        <input type="date" name="data_spesa" id="data_spesa_add" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="pagato_da_add" class="block text-sm font-medium text-stone-700">Pagato Da</label>
                        <select name="pagato_da" id="pagato_da_add" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                            <option value="">-- Seleziona --</option>
                            <?php foreach ($partecipanti as $p): ?>
                                <option value="<?php echo $p; ?>"><?php echo $p; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                 <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="luogo_id_add" class="block text-sm font-medium text-stone-700">Luogo</label>
                        <select name="luogo_id" id="luogo_id_add" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                            <option value="">-- Nessuno --</option>
                            <?php foreach ($luoghi as $luogo): ?>
                                <option value="<?php echo $luogo['id']; ?>"><?php echo htmlspecialchars($luogo['nome']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="giorno_id_add" class="block text-sm font-medium text-stone-700">Giorno Associato</label>
                        <select name="giorno_id" id="giorno_id_add" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                            <option value="">-- Nessuno --</option>
                            <?php foreach ($giorni_select as $giorno): ?>
                                <option value="<?php echo $giorno['id']; ?>">G<?php echo $giorno['giorno_num']; ?>: <?php echo htmlspecialchars($giorno['data']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="metodo_pagamento_add" class="block text-sm font-medium text-stone-700">Metodo di Pagamento</label>
                        <select name="metodo_pagamento" id="metodo_pagamento_add" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                            <option value="">-- Nessuno --</option>
                            <?php foreach ($metodi_pagamento as $metodo): ?>
                                <option value="<?php echo $metodo; ?>"><?php echo $metodo; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                 <div>
                    <label class="block text-sm font-medium text-stone-700">Diviso Per</label>
                    <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                        <?php foreach ($partecipanti as $p): ?>
                            <label class="flex items-center">
                                <input type="checkbox" name="diviso_per[]" value="<?php echo $p; ?>" class="h-4 w-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                <span class="ml-2 text-sm text-stone-600"><?php echo $p; ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                 <div>
                    <label for="note_add" class="block text-sm font-medium text-stone-700">Note</label>
                    <textarea name="note" id="note_add" rows="2" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm"></textarea>
                </div>

                <div class="pt-5">
                    <div class="flex justify-end">
                        <button type="submit" name="action" value="aggiungi" class="nav-button text-base">
                            Aggiungi Spesa
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
                    button.textContent = content.classList.contains('hidden') ? 'Dettagli' : 'Nascondi';
                });
            });
        });
    </script>
</body>
</html>
