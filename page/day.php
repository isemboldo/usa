<?php
// 1. Definiamo la costante di sicurezza.
define('ABSPATH', true);

// 2. Includiamo il file di configurazione dalla stessa cartella.
require_once 'config.php';

// 3. Stabiliamo la connessione al database.
try {
    $pdo = connect_db();
} catch (PDOException $e) {
    die("Impossibile connettersi al database. Si prega di riprovare più tardi.");
}

// 4. Otteniamo il numero del giorno dall'URL.
$giorno_attuale_num = filter_input(INPUT_GET, 'giorno', FILTER_VALIDATE_INT);
if (!$giorno_attuale_num) {
    header('Location: /usa/index.php');
    exit;
}

// 5. Estraiamo i dati del giorno corrente.
$stmt_giorno = $pdo->prepare("SELECT g.*, p.slug AS parte_slug FROM giorni g JOIN parti p ON g.parte_id = p.id WHERE g.giorno_num = ?");
$stmt_giorno->execute([$giorno_attuale_num]);
$giorno_attuale_dati = $stmt_giorno->fetch();

if (!$giorno_attuale_dati) {
    header('Location: /usa/index.php');
    exit;
}

// 6. Estraiamo le sezioni E i loro feedback con una JOIN
$stmt_sezioni = $pdo->prepare("
    SELECT s.*, f.likes, f.dislikes, f.more_info 
    FROM sezioni s
    LEFT JOIN feedback_sezioni f ON s.id = f.sezione_id
    WHERE s.giorno_id = ? 
    ORDER BY s.ordine ASC
");
$stmt_sezioni->execute([$giorno_attuale_dati['id']]);
$sezioni = $stmt_sezioni->fetchAll();

// 7. Calcoliamo i link per la navigazione.
$stmt_nav = $pdo->prepare("SELECT giorno_num FROM giorni WHERE giorno_num < ? ORDER BY giorno_num DESC LIMIT 1");
$stmt_nav->execute([$giorno_attuale_num]);
$giorno_precedente = $stmt_nav->fetchColumn();

$stmt_nav = $pdo->prepare("SELECT giorno_num FROM giorni WHERE giorno_num > ? ORDER BY giorno_num ASC LIMIT 1");
$stmt_nav->execute([$giorno_attuale_num]);
$giorno_successivo = $stmt_nav->fetchColumn();

$hub_sezione = $giorno_attuale_dati['parte_slug'];

// 8. Estraiamo TUTTI i giorni per il modal di navigazione
$stmt_tutti_giorni = $pdo->query("SELECT g.giorno_num, g.titolo, p.titolo AS titolo_parte FROM giorni g JOIN parti p ON g.parte_id = p.id ORDER BY p.id ASC, g.giorno_num ASC");
$lista_giorni_completa = $stmt_tutti_giorni->fetchAll(PDO::FETCH_ASSOC);

$tutti_giorni_raggruppati = [];
foreach ($lista_giorni_completa as $giorno_nav) {
    $tutti_giorni_raggruppati[$giorno_nav['titolo_parte']][] = $giorno_nav;
}

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giorno <?php echo $giorno_attuale_dati['giorno_num']; ?>: <?php echo htmlspecialchars($giorno_attuale_dati['titolo']); ?> - Guida USA 2026</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/usa/assets/css/style.css">
</head>
<body>

    <header class="main-header">
        <div class="main-container flex justify-between items-center h-16">
            <a href="/usa/" class="text-xl font-bold text-stone-900 hover:text-purple-600">Viaggio USA 2026</a>
            <nav class="hidden md:flex items-center space-x-2">
                <a href="/usa/page/hub.php?sezione=new-york" class="nav-link <?php echo ($hub_sezione === 'new-york') ? 'active' : ''; ?>">New York</a>
                <a href="/usa/page/hub.php?sezione=il-sud" class="nav-link <?php echo ($hub_sezione === 'il-sud') ? 'active' : ''; ?>">Il Sud</a>
                <a href="/usa/page/hub.php?sezione=orlando" class="nav-link <?php echo ($hub_sezione === 'orlando') ? 'active' : ''; ?>">Orlando</a>
                <a href="/usa/map/index.php" class="nav-link">Mappa</a>
                <a href="/usa/page/link.php" class="nav-link">Link Utili</a>
            </nav>
            <div class="md:hidden">
                <button id="mobile-menu-button" class="p-2 rounded-md text-stone-600 hover:text-purple-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden">
            <a href="/usa/page/hub.php?sezione=new-york" class="block py-2 px-4 text-sm text-stone-700 hover:bg-stone-200">New York</a>
            <a href="/usa/page/hub.php?sezione=il-sud" class="block py-2 px-4 text-sm text-stone-700 hover:bg-stone-200">Il Sud</a>
            <a href="/usa/page/hub.php?sezione=orlando" class="block py-2 px-4 text-sm text-stone-700 hover:bg-stone-200">Orlando</a>
            <a href="/usa/map/index.php" class="block py-2 px-4 text-sm text-stone-700 hover:bg-stone-200">Mappa</a>
            <a href="/usa/page/link.php" class="block py-2 px-4 text-sm text-stone-700 hover:bg-stone-200">Link Utili</a>
        </div>
    </header>
    <main>
        <div class="relative h-80 w-full flex items-center justify-center bg-cover bg-center image-overlay-container" style="background-image: url('<?php echo htmlspecialchars($giorno_attuale_dati['immagine_copertina']); ?>');">
            <div class="day-hero-overlay"></div>
            <div class="relative text-center p-4 image-overlay-content day-hero-content">
                <p class="text-lg font-semibold"><?php echo 'Giorno ' . $giorno_attuale_dati['giorno_num'] . ': ' . htmlspecialchars($giorno_attuale_dati['data']); ?></p>
                <h1 class="text-4xl md:text-5xl font-bold tracking-tight mt-2"><?php echo htmlspecialchars($giorno_attuale_dati['titolo']); ?></h1>
            </div>
        </div>

        <div class="bg-stone-100">
            <div class="main-container py-12">
                <div class="space-y-8 text-lg">
                    <?php foreach ($sezioni as $index => $sezione): ?>
                        <div class="day-section-card">
                            <div class="md:grid md:grid-cols-12 gap-8 items-center">
                                <?php $is_image_left = $index % 2 === 0; ?>
                                <div class="md:col-span-4 mb-4 md:mb-0 <?php echo $is_image_left ? '' : 'md:order-last'; ?>">
                                    <div class="day-image-container">
                                        <img src="<?php echo htmlspecialchars($sezione['immagine']); ?>" alt="<?php echo htmlspecialchars($sezione['titolo']); ?>">
                                    </div>
                                </div>
                                <div class="md:col-span-8">
                                    <?php if (!empty($sezione['sovratitolo'])): ?>
                                        <p class="day-section-pretitle"><?php echo htmlspecialchars($sezione['sovratitolo']); ?></p>
                                    <?php endif; ?>
                                    <h3 class="text-2xl font-bold text-stone-900 mb-2"><?php echo htmlspecialchars($sezione['titolo']); ?></h3>
                                    <div class="space-y-4"><?php echo $sezione['testo']; ?></div>
                                    
                                    <div class="feedback-section" data-sezione-id="<?php echo $sezione['id']; ?>">
                                        <button class="feedback-button" data-type="likes" title="Mi piace questa attività">👍<span data-counter="likes"><?php echo $sezione['likes'] ?? 0; ?></span></button>
                                        <button class="feedback-button" data-type="dislikes" title="Non mi piace questa attività">👎<span data-counter="dislikes"><?php echo $sezione['dislikes'] ?? 0; ?></span></button>
                                        <button class="feedback-button" data-type="more_info" title="Vorrei più informazioni su questo">ℹ️<span data-counter="more_info"><?php echo $sezione['more_info'] ?? 0; ?></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <nav class="bg-white border-t border-stone-200 py-4 sticky bottom-0 z-40">
            <div class="main-container flex flex-wrap justify-center md:justify-between items-center gap-2">
                <div class="w-1/3 md:w-auto text-left">
                    <?php if ($giorno_precedente): ?>
                        <a href="/usa/page/day.php?giorno=<?php echo $giorno_precedente; ?>" class="nav-button">
                            <span class="hidden sm:inline">&larr; Giorno Prec.</span>
                            <span class="sm:hidden">&larr; Prec.</span>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="w-full order-first md:order-none md:w-auto text-center mb-2 md:mb-0">
                    <button id="open-modal-button" class="nav-button w-full md:w-auto">☰ Tutti i Giorni</button>
                </div>
                <div class="w-1/3 md:w-auto text-right">
                    <?php if ($giorno_successivo): ?>
                        <a href="/usa/page/day.php?giorno=<?php echo $giorno_successivo; ?>" class="nav-button">
                            <span class="hidden sm:inline">Giorno Succ. &rarr;</span>
                            <span class="sm:hidden">Succ. &rarr;</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </main>

    <!-- Modal "Tutti i Giorni" -->
    <div id="days-modal" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold">Indice del Viaggio</h3>
                <button id="close-modal-button" class="modal-close-button">&times;</button>
            </div>
            <div class="modal-body">
                <?php foreach ($tutti_giorni_raggruppati as $titolo_parte => $giorni_in_parte): ?>
                    <h4 class="text-xl font-bold text-purple-600 mt-6 mb-2"><?php echo htmlspecialchars($titolo_parte); ?></h4>
                    <ul class="space-y-1">
                        <?php foreach ($giorni_in_parte as $giorno_nav): ?>
                            <li>
                                <a href="/usa/page/day.php?giorno=<?php echo $giorno_nav['giorno_num']; ?>" class="modal-link">
                                    <strong>Giorno <?php echo $giorno_nav['giorno_num']; ?>:</strong> <?php echo htmlspecialchars($giorno_nav['titolo']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script per il menù mobile
            document.getElementById('mobile-menu-button').addEventListener('click', function() {
                document.getElementById('mobile-menu').classList.toggle('hidden');
            });

            // Script per le animazioni al scroll
            const cards = document.querySelectorAll('.day-section-card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            cards.forEach(card => {
                observer.observe(card);
            });

            // Script per il Modal "Tutti i Giorni"
            const modal = document.getElementById('days-modal');
            const openModalBtn = document.getElementById('open-modal-button');
            const closeModalBtn = document.getElementById('close-modal-button');

            openModalBtn.addEventListener('click', () => modal.classList.remove('hidden'));
            closeModalBtn.addEventListener('click', () => modal.classList.add('hidden'));
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });

            // Script per i Feedback
            const feedbackSections = document.querySelectorAll('.feedback-section');
            const votedSections = JSON.parse(localStorage.getItem('votedSections')) || {};

            feedbackSections.forEach(section => {
                const sezioneId = section.dataset.sezioneId;
                const buttons = section.querySelectorAll('.feedback-button');

                if (votedSections[sezioneId]) {
                    buttons.forEach(button => {
                        button.classList.add('disabled');
                        if (button.dataset.type === votedSections[sezioneId]) {
                            button.classList.add('voted');
                        }
                    });
                } else {
                    buttons.forEach(button => {
                        button.addEventListener('click', async () => {
                            const type = button.dataset.type;
                            buttons.forEach(btn => btn.classList.add('disabled'));

                            try {
                                const response = await fetch('/usa/admin/gestisci_feedback.php', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({ sezione_id: sezioneId, type: type })
                                });
                                const result = await response.json();

                                if (result.success) {
                                    section.querySelector('[data-counter="likes"]').textContent = result.data.likes;
                                    section.querySelector('[data-counter="dislikes"]').textContent = result.data.dislikes;
                                    section.querySelector('[data-counter="more_info"]').textContent = result.data.more_info;
                                    button.classList.add('voted');
                                    votedSections[sezioneId] = type;
                                    localStorage.setItem('votedSections', JSON.stringify(votedSections));
                                } else {
                                    buttons.forEach(btn => btn.classList.remove('disabled'));
                                    console.error('Errore dal server:', result.error);
                                }
                            } catch (error) {
                                buttons.forEach(btn => btn.classList.remove('disabled'));
                                console.error('Errore di rete:', error);
                            }
                        });
                    });
                }
            });
        });
    </script>
</body>
</html>
