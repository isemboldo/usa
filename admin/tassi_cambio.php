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

// 4. Gestione dell'aggiornamento dei tassi di cambio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salva_tassi'])) {
    if (isset($_POST['tassi'])) {
        $sql = "UPDATE tassi_cambio SET tasso_a_chf = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        foreach ($_POST['tassi'] as $id => $tasso) {
            $stmt->execute([$tasso, $id]);
        }
        $messaggio = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">Tassi di cambio aggiornati con successo!</div>';
    }
}

// 5. Estraiamo i tassi di cambio attuali dal database
$tassi = $pdo->query("SELECT * FROM tassi_cambio ORDER BY valuta ASC")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Tassi di Cambio - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/usa/assets/css/style.css">
</head>
<body class="bg-stone-100">

    <header class="bg-white text-stone-800 shadow-lg">
        <div class="main-container flex justify-between items-center h-16">
            <h1 class="text-xl font-bold">Gestione Tassi di Cambio</h1>
            <div>
                <a href="index.php" class="text-sm font-semibold text-purple-600 hover:underline">&larr; Torna alla Dashboard</a>
            </div>
        </div>
    </header>

    <main class="main-container py-12">
        
        <?php if ($messaggio) echo $messaggio; ?>

        <div class="bg-white p-8 rounded-xl shadow-lg">
            <h2 class="text-3xl font-bold text-stone-900 mb-2">Tassi di Cambio</h2>
            <p class="text-stone-600 mb-6">Modifica i tassi di conversione verso CHF che verranno usati in tutto il portale.</p>
            
            <form action="tassi_cambio.php" method="POST">
                <div class="space-y-4">
                    <?php foreach ($tassi as $tasso): ?>
                        <div class="grid grid-cols-3 items-center gap-4">
                            <label for="tasso_<?php echo $tasso['id']; ?>" class="font-bold text-lg text-stone-700">
                                1 <?php echo htmlspecialchars($tasso['valuta']); ?> =
                            </label>
                            <input type="number" step="0.000001" name="tassi[<?php echo $tasso['id']; ?>]" id="tasso_<?php echo $tasso['id']; ?>" value="<?php echo htmlspecialchars($tasso['tasso_a_chf']); ?>" class="col-span-1 mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                            <span class="font-bold text-lg text-stone-700">CHF</span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="pt-8 mt-6 border-t border-stone-200">
                    <div class="flex justify-end">
                        <button type="submit" name="salva_tassi" class="nav-button text-base">
                            Salva Tassi di Cambio
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
