<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Nostra Avventura Americana 2026</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/usa/assets/css/style.css">
</head>
<body>

    <div class="relative h-screen w-full flex items-center justify-center bg-cover bg-center" style="background-image: url('/usa/assets/images/giorno-1.jpg');">
        <div class="hero-overlay"></div>
        <div class="relative text-center p-4 image-overlay-content">
            <h1 class="text-5xl md:text-7xl font-bold tracking-tight">La Nostra Avventura Americana 2026</h1>
            <p class="mt-4 text-lg md:text-xl max-w-2xl mx-auto text-stone-200">Il diario di un viaggio indimenticabile, dalle luci di New York al cuore del Sud, fino alla magia di Orlando.</p>
        </div>
    </div>

    <div class="bg-stone-100 py-16 sm:py-24">
        <div class="main-container">
            <div class="text-center mb-12">
                 <h2 class="text-3xl md:text-4xl font-bold">Esplora il Viaggio</h2>
                 <p class="mt-2 text-lg text-stone-600">Scegli una parte della nostra avventura per iniziare.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <a href="/usa/page/hub.php?sezione=new-york" class="nav-card">
                    <div class="image-overlay-container h-56 w-full">
                        <img class="h-full w-full object-cover" src="/usa/assets/images/giorno-5.jpg" alt="Ponte di Brooklyn e skyline di Manhattan">
                        <div class="image-overlay-gradient"></div>
                        <div class="absolute bottom-0 left-0 p-6 image-overlay-content">
                            <h3 class="text-2xl font-bold">🗽 New York</h3>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col justify-between flex-grow">
                        <p>Cinque giorni tra icone, arte e sapori unici.</p>
                        <div class="mt-4">
                            <span class="cta-button-sm">Esplora &rarr;</span>
                        </div>
                    </div>
                </a>

                <a href="/usa/page/hub.php?sezione=il-sud" class="nav-card">
                    <div class="image-overlay-container h-56 w-full">
                        <img class="h-full w-full object-cover" src="/usa/assets/images/giorno-9.jpg" alt="Viale di querce in una piantagione del Sud">
                        <div class="image-overlay-gradient"></div>
                        <div class="absolute bottom-0 left-0 p-6 image-overlay-content">
                            <h3 class="text-2xl font-bold">🚗 Il Sud</h3>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col justify-between flex-grow">
                        <p>Un viaggio on the road tra storia e natura.</p>
                        <div class="mt-4">
                            <span class="cta-button-sm">Esplora &rarr;</span>
                        </div>
                    </div>
                </a>

                <a href="/usa/page/hub.php?sezione=orlando" class="nav-card">
                    <div class="image-overlay-container h-56 w-full">
                        <img class="h-full w-full object-cover" src="/usa/assets/images/giorno-16.jpg" alt="Castello incantato in un parco a tema">
                        <div class="image-overlay-gradient"></div>
                        <div class="absolute bottom-0 left-0 p-6 image-overlay-content">
                            <h3 class="text-2xl font-bold">🎢 Orlando</h3>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col justify-between flex-grow">
                        <p>Un tuffo finale nell'avventura e nella fantasia.</p>
                        <div class="mt-4">
                            <span class="cta-button-sm">Esplora &rarr;</span>
                        </div>
                    </div>
                </a>

                <a href="/usa/map/index.php" class="nav-card">
                    <div class="image-overlay-container h-56 w-full">
                        <img class="h-full w-full object-cover" src="/usa/assets/images/map-card.jpg" alt="Mappa stilizzata della East Coast">
                        <div class="image-overlay-gradient"></div>
                        <div class="absolute bottom-0 left-0 p-6 image-overlay-content">
                            <h3 class="text-2xl font-bold">🗺️ Mappa</h3>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col justify-between flex-grow">
                        <p>Visualizza l'intero itinerario su una mappa interattiva.</p>
                        <div class="mt-4">
                            <span class="cta-button-sm">Visualizza Mappa &rarr;</span>
                        </div>
                    </div>
                </a>
                
                <a href="/usa/page/rendiconto.php" class="nav-card">
                    <div class="image-overlay-container h-56 w-full">
                        <img class="h-full w-full object-cover" src="/usa/assets/images/money.jpg" alt="Calcolatrice e appunti per il budget">
                        <div class="image-overlay-gradient"></div>
                        <div class="absolute bottom-0 left-0 p-6 image-overlay-content">
                            <h3 class="text-2xl font-bold">💰 Rendiconto</h3>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col justify-between flex-grow">
                        <p>Consulta i costi previsti e lo stato dei pagamenti per ogni partecipante.</p>
                        <div class="mt-4">
                            <span class="cta-button-sm">Vedi Dettagli &rarr;</span>
                        </div>
                    </div>
                </a>

                <a href="/usa/page/link.php" class="nav-card">
                    <div class="image-overlay-container h-56 w-full">
                        <img class="h-full w-full object-cover" src="/usa/assets/images/link.jpg" alt="Taccuino con link e risorse di viaggio">
                        <div class="image-overlay-gradient"></div>
                        <div class="absolute bottom-0 left-0 p-6 image-overlay-content">
                            <h3 class="text-2xl font-bold">🔗 Link Utili</h3>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col justify-between flex-grow">
                        <p>Accedi a tutti i siti ufficiali per attrazioni, hotel e ristoranti.</p>
                        <div class="mt-4">
                            <span class="cta-button-sm">Consulta i Link &rarr;</span>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </div>

</body>
</html>
