<?php
define('ABSPATH', true);
// Avviamo la sessione qui per gestire i messaggi di iscrizione
session_start();
require_once 'config.php';

try {
    $pdo = connect_db();
} catch (PDOException $e) {
    die("Impossibile connettersi al database. Si prega di riprovare più tardi.");
}

$sezione_attuale_slug = $_GET['sezione'] ?? null;
if (!$sezione_attuale_slug) {
    header('Location: /usa/index.php');
    exit;
}

$stmt_parte = $pdo->prepare("SELECT * FROM parti WHERE slug = ?");
$stmt_parte->execute([$sezione_attuale_slug]);
$parte = $stmt_parte->fetch();

if (!$parte) {
    header('Location: /usa/index.php');
    exit;
}

// AGGIORNATO: Selezioniamo anche la data di modifica e il riassunto
$stmt_giorni = $pdo->prepare("SELECT id, giorno_num, titolo, immagine_copertina, riassunto, data_modifica FROM giorni WHERE parte_id = ? ORDER BY giorno_num ASC");
$stmt_giorni->execute([$parte['id']]);
$giorni_sezione = $stmt_giorni->fetchAll();

// Gestione dei messaggi di iscrizione
$messaggio_iscrizione = $_SESSION['messaggio_iscrizione'] ?? null;
unset($_SESSION['messaggio_iscrizione']);

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($parte['titolo']); ?> - Guida di Viaggio USA 2026</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="/usa/assets/css/style.css">
</head>
<body>

    <header class="main-header">
        <div class="main-container flex justify-between items-center h-16">
            <a href="/usa/" class="text-xl font-bold text-stone-900 hover:text-purple-600">Viaggio USA 2026</a>
            <nav class="hidden md:flex items-center space-x-2">
                <a href="/usa/page/hub.php?sezione=new-york" class="nav-link <?php echo ($sezione_attuale_slug === 'new-york') ? 'active' : ''; ?>">New York</a>
                <a href="/usa/page/hub.php?sezione=il-sud" class="nav-link <?php echo ($sezione_attuale_slug === 'il-sud') ? 'active' : ''; ?>">Il Sud</a>
                <a href="/usa/page/hub.php?sezione=orlando" class="nav-link <?php echo ($sezione_attuale_slug === 'orlando') ? 'active' : ''; ?>">Orlando</a>
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

    <main class="main-container py-12">
        
        <div class="text-center mb-12">
             <h1 class="text-4xl md:text-5xl font-bold"><?php echo htmlspecialchars($parte['titolo']); ?></h1>
        </div>

        <div class="swiper hub-carousel mb-12">
            <div class="swiper-wrapper">
                <?php 
                $immagini_carousel = array_slice($giorni_sezione, 0, 5);
                foreach ($immagini_carousel as $giorno_img): 
                ?>
                    <div class="swiper-slide">
                        <img src="<?php echo htmlspecialchars($giorno_img['immagine_copertina']); ?>" alt="Immagine per <?php echo htmlspecialchars($giorno_img['titolo']); ?>" class="w-full h-full object-cover">
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
        
        <p class="section-description"><?php echo htmlspecialchars($parte['descrizione']); ?></p>

        <div class="mb-12">
            <button id="toggle-summary" class="summary-button">
                <span>L'Itinerario in Breve</span>
                <span id="summary-arrow">▼</span>
            </button>
            <div id="summary-content" class="hidden bg-white p-6 rounded-b-lg border-t border-stone-200">
                <ul class="list-none space-y-4">
                    <?php foreach ($giorni_sezione as $giorno_riassunto): ?>
                        <li><strong>Giorno <?php echo $giorno_riassunto['giorno_num']; ?>: <?php echo htmlspecialchars($giorno_riassunto['titolo']); ?></strong> - <?php echo htmlspecialchars($giorno_riassunto['riassunto']); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($giorni_sezione as $giorno): ?>
                <a href="/usa/page/day.php?giorno=<?php echo $giorno['giorno_num']; ?>" 
                   class="nav-card" 
                   data-giorno-id="<?php echo $giorno['id']; ?>" 
                   data-last-modified="<?php echo strtotime($giorno['data_modifica']); ?>">
                    
                    <div class="image-overlay-container h-56 w-full">
                        <div class="new-badge hidden">Novità!</div>
                        <img class="h-full w-full object-cover" src="<?php echo htmlspecialchars($giorno['immagine_copertina']); ?>" alt="<?php echo htmlspecialchars($giorno['titolo']); ?>">
                        <div class="image-overlay-gradient"></div>
                        <div class="absolute bottom-0 left-0 p-6 image-overlay-content">
                            <h3 class="text-2xl font-bold">Giorno <?php echo $giorno['giorno_num']; ?></h3>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col justify-between flex-grow">
                        <div>
                           <h4 class="font-bold text-xl mb-2 text-stone-900"><?php echo htmlspecialchars($giorno['titolo']); ?></h4>
                        </div>
                        <div class="mt-4">
                           <span class="cta-button-sm">Scopri i dettagli &rarr;</span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </main>

    <footer class="bg-white border-t border-stone-200 py-12">
        <div class="main-container">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div>
                    <h3 class="text-2xl font-bold text-stone-900">Rimani Aggiornato</h3>
                    <p class="text-stone-600 mt-2">Iscriviti per ricevere una notifica via email ogni volta che ci sono novità importanti sull'itinerario.</p>
                </div>
                <div>
                    <form action="/usa/page/iscriviti.php" method="POST" class="flex flex-col sm:flex-row gap-2">
                        <input type="hidden" name="redirect_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                        <input type="text" name="nome" placeholder="Il tuo nome" required class="w-full px-4 py-2 border border-stone-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                        <input type="email" name="email" placeholder="La tua email" required class="w-full px-4 py-2 border border-stone-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                        <button type="submit" class="nav-button flex-shrink-0">Iscriviti</button>
                    </form>
                    <?php if ($messaggio_iscrizione): ?>
                        <p class="mt-2 text-sm <?php echo ($messaggio_iscrizione['tipo'] === 'successo') ? 'text-green-600' : 'text-red-600'; ?>">
                            <?php echo htmlspecialchars($messaggio_iscrizione['testo']); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script per carousel, menù mobile e riassunto
            var swiper = new Swiper(".hub-carousel", {
                loop: true,
                autoplay: { delay: 5000, disableOnInteraction: false },
                pagination: { el: ".swiper-pagination", clickable: true },
                navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
            });
            document.getElementById('toggle-summary').addEventListener('click', function() {
                var content = document.getElementById('summary-content');
                var arrow = document.getElementById('summary-arrow');
                content.classList.toggle('hidden');
                arrow.textContent = content.classList.contains('hidden') ? '▼' : '▲';
            });
            document.getElementById('mobile-menu-button').addEventListener('click', function() {
                document.getElementById('mobile-menu').classList.toggle('hidden');
            });

            // Logica per le notifiche di aggiornamento
            const viewedDaysKey = 'viewedDaysTimestamps';
            let viewedDays = JSON.parse(localStorage.getItem(viewedDaysKey)) || {};
            
            const dayCards = document.querySelectorAll('.nav-card[data-giorno-id]');

            dayCards.forEach(card => {
                const giornoId = card.dataset.giornoId;
                const lastModifiedTimestamp = parseInt(card.dataset.lastModified, 10);
                const lastViewedTimestamp = viewedDays[giornoId] || 0;

                if (lastModifiedTimestamp > lastViewedTimestamp) {
                    card.classList.add('is-new');
                    const badge = card.querySelector('.new-badge');
                    if (badge) {
                        badge.classList.remove('hidden');
                    }
                }

                card.addEventListener('click', () => {
                    const nowTimestamp = Math.floor(Date.now() / 1000);
                    viewedDays[giornoId] = nowTimestamp;
                    localStorage.setItem(viewedDaysKey, JSON.stringify(viewedDays));
                });
            });
        });
    </script>
</body>
</html>
