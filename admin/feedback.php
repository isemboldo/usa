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

// 4. Estraiamo tutti i feedback dal database, unendoli con le informazioni del giorno e della sezione.
// Mostriamo solo le sezioni che hanno ricevuto almeno un feedback.
$stmt = $pdo->query("
    SELECT 
        g.giorno_num,
        s.titolo AS titolo_sezione,
        f.likes,
        f.dislikes,
        f.more_info
    FROM feedback_sezioni f
    JOIN sezioni s ON f.sezione_id = s.id
    JOIN giorni g ON s.giorno_id = g.id
    WHERE f.likes > 0 OR f.dislikes > 0 OR f.more_info > 0
    ORDER BY g.giorno_num ASC, s.ordine ASC
");
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riepilogo Feedback - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/usa/assets/css/style.css">
</head>
<body class="bg-stone-100">

    <header class="bg-white text-stone-800 shadow-lg">
        <div class="main-container flex justify-between items-center h-16">
            <h1 class="text-xl font-bold">Riepilogo Feedback</h1>
            <div>
                <a href="index.php" class="text-sm font-semibold text-purple-600 hover:underline">&larr; Torna alla Dashboard</a>
            </div>
        </div>
    </header>

    <main class="main-container py-12">
        <div class="bg-white p-8 rounded-xl shadow-lg">
            <h2 class="text-3xl font-bold text-stone-900 mb-6">Feedback Ricevuti</h2>
            
            <?php if (empty($feedbacks)): ?>
                <p class="text-stone-600">Nessun feedback ricevuto finora.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200">
                        <thead class="bg-stone-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Giorno</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-stone-500 uppercase tracking-wider">Sezione Attività</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-stone-500 uppercase tracking-wider">👍 Mi Piace</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-stone-500 uppercase tracking-wider">👎 Non Mi Piace</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-stone-500 uppercase tracking-wider">ℹ️ Più Info</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-stone-200">
                            <?php foreach ($feedbacks as $feedback): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-bold text-stone-800">Giorno <?php echo $feedback['giorno_num']; ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-stone-700"><?php echo htmlspecialchars($feedback['titolo_sezione']); ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-lg font-bold text-green-600"><?php echo $feedback['likes']; ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-lg font-bold text-red-600"><?php echo $feedback['dislikes']; ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-lg font-bold text-blue-600"><?php echo $feedback['more_info']; ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>
