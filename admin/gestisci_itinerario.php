<?php
// 1. Definiamo la costante di sicurezza e avviamo la sessione
define('ABSPATH', true);
session_start();

// 2. Controllo di Sicurezza.
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: /usa/admin/login.php');
    exit;
}

// Includiamo la configurazione del DB
require_once '../page/config.php';
try {
    $pdo = connect_db();
} catch (PDOException $e) {
    die("Impossibile connettersi al database: " . $e->getMessage());
}

// 4. Estraiamo tutti i giorni dal database, unendoli con le rispettive parti e concatenando i titoli delle sezioni.
$stmt = $pdo->query("
    SELECT 
        g.id, 
        g.giorno_num, 
        g.titolo, 
        p.titolo AS titolo_parte,
        GROUP_CONCAT(s.titolo ORDER BY s.ordine SEPARATOR ' | ') AS sezioni_titoli
    FROM giorni g
    JOIN parti p ON g.parte_id = p.id
    LEFT JOIN sezioni s ON s.giorno_id = g.id
    GROUP BY g.id
    ORDER BY p.id ASC, g.giorno_num ASC
");
$tutti_i_giorni = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 5. Raggruppiamo i giorni per sezione per una visualizzazione più ordinata.
$giorni_raggruppati = [];
foreach ($tutti_i_giorni as $giorno) {
    $giorni_raggruppati[$giorno['titolo_parte']][] = $giorno;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestisci Itinerario - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/usa/assets/css/style.css">
</head>
<body class="bg-stone-100">

    <header class="bg-white text-stone-800 shadow-lg">
        <div class="main-container flex justify-between items-center h-16">
            <h1 class="text-xl font-bold">Gestisci Itinerario</h1>
            <div>
                <a href="index.php" class="text-sm font-semibold text-purple-600 hover:underline">&larr; Torna alla Dashboard</a>
            </div>
        </div>
    </header>

    <main class="main-container py-12">
        <div class="space-y-10">
            <?php foreach ($giorni_raggruppati as $titolo_parte => $giorni_in_parte): ?>
                <section>
                    <h3 class="text-2xl font-bold text-purple-600 border-b-2 border-purple-200 pb-2 mb-4"><?php echo htmlspecialchars($titolo_parte); ?></h3>
                    <div class="bg-white rounded-xl shadow-lg">
                        <ul class="divide-y divide-stone-200">
                            <?php foreach ($giorni_in_parte as $giorno): ?>
                                <li class="p-4 flex justify-between items-start gap-4">
                                    <div class="flex-grow">
                                        <span class="text-stone-500 font-semibold">Giorno <?php echo $giorno['giorno_num']; ?></span>
                                        <p class="text-lg font-bold text-stone-800"><?php echo htmlspecialchars($giorno['titolo']); ?></p>
                                        <?php if (!empty($giorno['sezioni_titoli'])): ?>
                                            <p class="text-xs text-stone-500 mt-1">
                                                <strong>Contenuti:</strong> <?php echo htmlspecialchars($giorno['sezioni_titoli']); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-shrink-0 mt-1">
                                        <a href="modifica_giorno.php?id=<?php echo $giorno['id']; ?>" class="nav-button">
                                            Modifica
                                        </a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
    </main>

</body>
</html>
