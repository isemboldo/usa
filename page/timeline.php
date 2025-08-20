<?php
// 1. Definiamo la costante di sicurezza e avviamo la sessione.
define('ABSPATH', true);
session_start();

// 2. Includiamo la configurazione e ci connettiamo al database.
require_once 'config.php';
try {
    $pdo = connect_db();
} catch (PDOException $e) {
    die("Impossibile connettersi al database. Si prega di riprovare più tardi.");
}

// 3. Aumentiamo il limite per GROUP_CONCAT per la sessione corrente.
$pdo->exec('SET SESSION group_concat_max_len = 1000000');

// 4. Estraiamo tutti i dati necessari
$stmt_giorni = $pdo->query("
    SELECT 
        g.id,
        g.giorno_num, 
        g.data, 
        g.titolo,
        g.immagine_copertina,
        p.titolo AS titolo_parte
    FROM giorni g
    JOIN parti p ON g.parte_id = p.id
    ORDER BY g.giorno_num ASC
");
$giorni = $stmt_giorni->fetchAll(PDO::FETCH_ASSOC);

$stmt_sezioni = $pdo->prepare("SELECT sovratitolo, titolo, immagine, testo FROM sezioni WHERE giorno_id = ? ORDER BY ordine ASC");

// Funzione per estrarre le parole in grassetto
function estrai_keywords($html) {
    $keywords = [];
    if (preg_match_all('/<strong>(.*?)<\/strong>/', $html, $matches)) {
        foreach ($matches[1] as $match) {
            $keyword = strip_tags(trim($match));
            if (!empty($keyword)) {
                $keywords[] = $keyword;
            }
        }
    }
    return array_unique($keywords);
}

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeline del Viaggio - Guida USA 2026</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/usa/assets/css/style.css">
</head>
<body class="bg-stone-900 text-white">

    <header class="main-header sticky top-0 left-0 right-0 z-50 bg-stone-900 shadow-lg">
        <div class="main-container relative flex justify-between items-center h-16">
            <a href="/usa/" class="text-xl font-bold text-white hover:text-stone-300" style="text-shadow: 0 1px 3px rgba(0,0,0,0.5);">Viaggio USA 2026</a>
            
            <div class="absolute left-1/2 -translate-x-1/2 flex flex-col items-center pointer-events-none text-center">
                <span id="dynamic-part-title" class="text-lg font-bold text-purple-400"></span>
                <span id="dynamic-day-title" class="text-sm text-stone-300 font-semibold"></span>
            </div>

        </div>
    </header>

    <div id="timeline-background-container">
        <?php foreach ($giorni as $giorno): ?>
            <div class="timeline-background-image" data-day="<?php echo $giorno['giorno_num']; ?>" style="background-image: url('<?php echo htmlspecialchars($giorno['immagine_copertina']); ?>');"></div>
        <?php endforeach; ?>
    </div>

    <main class="main-container py-12 pt-24">
        
        <div class="timeline-wrapper-v7">
            <aside class="timeline-nav-v7">
                <ul>
                    <?php foreach ($giorni as $giorno): ?>
                        <li>
                            <a href="#day-<?php echo $giorno['giorno_num']; ?>" data-nav-day="<?php echo $giorno['giorno_num']; ?>">
                                <span class="day-title"><?php echo htmlspecialchars($giorno['data']); ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <div class="timeline-content-v7">
                <div class="text-center mb-12 relative z-10">
                    <h1 class="text-4xl md:text-5xl font-bold text-white" style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);">Timeline del Viaggio</h1>
                    <p class="mt-4 text-lg text-stone-200" style="text-shadow: 0 1px 3px rgba(0,0,0,0.5);">La nostra avventura, tappa dopo tappa.</p>
                </div>
                
                <?php $current_parte = ''; $section_index = 0; ?>
                <?php foreach ($giorni as $giorno): ?>
                    <?php if ($giorno['titolo_parte'] !== $current_parte): ?>
                        <div class="timeline-part-header" data-title="<?php echo htmlspecialchars($giorno['titolo_parte']); ?>">
                            <h2 class="text-3xl font-bold text-purple-400"><?php echo htmlspecialchars($giorno['titolo_parte']); ?></h2>
                        </div>
                        <?php $current_parte = $giorno['titolo_parte']; ?>
                    <?php endif; ?>

                    <div id="day-<?php echo $giorno['giorno_num']; ?>" class="day-marker-v7" data-day-title="Giorno <?php echo $giorno['giorno_num']; ?>: <?php echo htmlspecialchars($giorno['titolo']); ?>">
                        <div class="day-marker-content">
                            <span class="day-num">Giorno <?php echo $giorno['giorno_num']; ?></span>
                            <h3 class="day-title"><?php echo htmlspecialchars($giorno['titolo']); ?></h3>
                        </div>
                    </div>

                    <div class="day-callout-v7">
                        <span class="font-bold text-purple-300">Giorno <?php echo $giorno['giorno_num']; ?></span>
                        <span class="text-stone-300"><?php echo htmlspecialchars($giorno['data']); ?></span>
                    </div>

                    <?php
                    $stmt_sezioni->execute([$giorno['id']]);
                    $sezioni = $stmt_sezioni->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <?php if (!empty($sezioni)): ?>
                        <?php foreach ($sezioni as $sezione): ?>
                            <div class="timeline-event-v7 <?php echo $section_index % 2 === 0 ? 'event-right' : 'event-left'; ?>">
                                <div class="event-dot-v7"></div>
                                <div class="event-content-v7">
                                    <div class="event-image-v7">
                                        <img src="<?php echo htmlspecialchars($sezione['immagine']); ?>" alt="<?php echo htmlspecialchars($sezione['titolo']); ?>">
                                    </div>
                                    <div class="event-text-v7">
                                        <?php if (!empty($sezione['sovratitolo'])): ?>
                                            <p class="day-section-pretitle"><?php echo htmlspecialchars($sezione['sovratitolo']); ?></p>
                                        <?php endif; ?>
                                        <h4 class="font-bold text-xl text-stone-100"><?php echo htmlspecialchars($sezione['titolo']); ?></h4>
                                        
                                        <?php $keywords = estrai_keywords($sezione['testo']); ?>
                                        <?php if(!empty($keywords)): ?>
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                <?php foreach($keywords as $keyword): ?>
                                                    <span class="text-xs bg-purple-200 text-purple-900 px-2 py-1 rounded-full font-semibold"><?php echo htmlspecialchars($keyword); ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php $section_index++; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Selettori
            const navLinks = document.querySelectorAll('.timeline-nav-v7 a');
            const dayAnchors = document.querySelectorAll('.day-marker-v7');
            const bgImages = document.querySelectorAll('.timeline-background-image');
            const events = document.querySelectorAll('.timeline-event-v7');
            const dynamicDayTitleEl = document.getElementById('dynamic-day-title');
            const dynamicPartTitleEl = document.getElementById('dynamic-part-title');
            const partHeaders = document.querySelectorAll('.timeline-part-header');

            // Observer per animare le card evento
            const eventObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, { threshold: 0.2, rootMargin: "0px 0px -50px 0px" });
            events.forEach(item => eventObserver.observe(item));
            
            // Impostiamo una "linea di attivazione" a circa il 40% dall'alto dello schermo
            const activationPoint = window.innerHeight * 0.4;

            // Creiamo un unico observer per tutti i marcatori (giorni e sezioni)
            const titleObserver = new IntersectionObserver(entries => {
                // Per ogni cambiamento rilevato dall'observer...
                
                // 1. Troviamo l'ultimo giorno attivo
                let lastActiveDay = null;
                dayAnchors.forEach(anchor => {
                    // Se l'ancora di un giorno si trova SOPRA il punto di attivazione, la consideriamo "attiva"
                    if (anchor.getBoundingClientRect().top < activationPoint) {
                        lastActiveDay = anchor;
                    }
                });

                // 2. Troviamo l'ultima sezione attiva
                let lastActivePart = null;
                partHeaders.forEach(header => {
                    if (header.getBoundingClientRect().top < activationPoint) {
                        lastActivePart = header;
                    }
                });

                // 3. Aggiorniamo i titoli nell'header con i dati trovati
                if (lastActiveDay) {
                    const dayNum = lastActiveDay.id.split('-')[1];
                    dynamicDayTitleEl.textContent = lastActiveDay.dataset.dayTitle;
                    
                    // Aggiorniamo anche la navigazione e lo sfondo
                    navLinks.forEach(link => link.classList.toggle('active', link.dataset.navDay === dayNum));
                    bgImages.forEach(img => img.classList.toggle('active', img.dataset.day === dayNum));
                }

                if (lastActivePart) {
                    dynamicPartTitleEl.textContent = lastActivePart.dataset.title;
                } else if (partHeaders.length > 0) {
                    // Se nessuna sezione è attiva (siamo all'inizio), mostriamo la prima
                    dynamicPartTitleEl.textContent = partHeaders[0].dataset.title;
                }

            }, {
                // Controlliamo gli elementi appena entrano o escono dallo schermo
                rootMargin: '0px',
                threshold: 0
            });

            // Avviamo l'observer per tutti i marcatori di giorno e sezione
            dayAnchors.forEach(anchor => titleObserver.observe(anchor));
            partHeaders.forEach(header => titleObserver.observe(header));
        });
    </script>
</body>
</html>