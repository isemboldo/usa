<?php
// Definiamo la costante di sicurezza per includere file in futuro se necessario
define('ABSPATH', true);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Utili - Guida Viaggio USA 2026</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/usa/assets/css/style.css">
</head>
<body>

    <header class="main-header">
        <div class="main-container flex justify-between items-center h-16">
            <a href="/usa/" class="text-xl font-bold text-stone-900 hover:text-purple-600">Viaggio USA 2026</a>
            <nav class="hidden md:flex items-center space-x-2">
                <a href="/usa/page/hub.php?sezione=new-york" class="nav-link">New York</a>
                <a href="/usa/page/hub.php?sezione=il-sud" class="nav-link">Il Sud</a>
                <a href="/usa/page/hub.php?sezione=orlando" class="nav-link">Orlando</a>
                <a href="/usa/map/index.php" class="nav-link">Mappa</a>
                <a href="/usa/page/link.php" class="nav-link active">Link Utili</a>
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
            <a href="/usa/page/link.php" class="block py-2 px-4 text-sm text-stone-700 hover:bg-stone-200 bg-stone-100 font-semibold">Link Utili</a>
        </div>
    </header>
    <main class="main-container py-12">
        <div class="text-center mb-12">
             <h1 class="text-4xl md:text-5xl font-bold">Link Utili per il Viaggio</h1>
             <p class="mt-4 text-lg text-stone-600">Una raccolta di tutti i siti ufficiali per attrazioni, hotel e ristoranti.</p>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg space-y-10">
            <!-- Sezione New York -->
            <div>
                <h2 class="text-3xl font-bold text-purple-600 border-b-2 border-purple-200 pb-2 mb-6">Parte 1: New York City</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <h3 class="text-xl font-bold text-stone-800 mb-3">Attrazioni e Musei</h3>
                        <ul class="link-list">
                            <li><strong>Giorno 2:</strong> <a href="https://www.topoftherocknyc.com/" target="_blank">Top of the Rock</a></li>
                            <li><strong>Giorno 2:</strong> <a href="https://www.faoschwarz.com/" target="_blank">FAO Schwarz</a></li>
                            <li><strong>Giorno 2:</strong> <a href="https://saintpatrickscathedral.org/" target="_blank">Cattedrale di San Patrizio</a></li>
                            <li><strong>Giorno 3:</strong> <a href="https://thestonewallinnnyc.com/" target="_blank">The Stonewall Inn</a></li>
                            <li><strong>Giorno 3:</strong> <a href="https://www.bitterend.com/" target="_blank">The Bitter End</a></li>
                            <li><strong>Giorno 3:</strong> <a href="https://www.tenement.org/" target="_blank">Tenement Museum</a></li>
                            <li><strong>Giorno 3:</strong> <a href="https://www.thehighline.org/" target="_blank">The High Line</a></li>
                            <li><strong>Giorno 3:</strong> <a href="https://www.chelseamarket.com/" target="_blank">Chelsea Market</a></li>
                            <li><strong>Giorno 3:</strong> <a href="https://littleisland.org/" target="_blank">Little Island</a></li>
                            <li><strong>Giorno 4:</strong> <a href="https://www.stjohndivine.org/" target="_blank">Cathedral of St. John the Divine</a></li>
                            <li><strong>Giorno 4:</strong> <a href="https://www.columbia.edu/" target="_blank">Columbia University</a></li>
                            <li><strong>Giorno 4:</strong> <a href="https://www.apollotheater.org/" target="_blank">Apollo Theater</a></li>
                            <li><strong>Giorno 4:</strong> <a href="https://www.studiomuseum.org/" target="_blank">Studio Museum in Harlem</a></li>
                            <li><strong>Giorno 5:</strong> <a href="https://www.ferry.nyc/" target="_blank">NYC Ferry</a></li>
                            <li><strong>Giorno 5:</strong> <a href="https://www.dominopark.com/" target="_blank">Domino Park</a></li>
                            <li><strong>Giorno 5:</strong> <a href="https://lunaparknyc.com/" target="_blank">Luna Park a Coney Island</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-stone-800 mb-3">Ristoranti e Locali</h3>
                        <ul class="link-list">
                            <li><strong>Giorno 2:</strong> <a href="https://www.shakeshack.com/" target="_blank">Shake Shack</a></li>
                            <li><strong>Giorno 3:</strong> <a href="https://www.caffereggio.com/" target="_blank">Caffe Reggio</a></li>
                            <li><strong>Giorno 3:</strong> <a href="https://www.katzsdelicatessen.com/" target="_blank">Katz's Delicatessen</a></li>
                            <li><strong>Giorno 4:</strong> <a href="https://www.sylviasrestaurant.com/" target="_blank">Sylvia's Restaurant</a></li>
                            <li><strong>Giorno 5:</strong> <a href="https://www.joespizzanyc.com/" target="_blank">Joe's Pizza</a></li>
                            <li><strong>Giorno 5:</strong> <a href="https://nathansfamous.com/" target="_blank">Nathan's Famous</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sezione Il Sud -->
            <div>
                <h2 class="text-3xl font-bold text-purple-600 border-b-2 border-purple-200 pb-2 mb-6">Parte 2: Il Sud degli Stati Uniti</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <h3 class="text-xl font-bold text-stone-800 mb-3">Attrazioni</h3>
                        <ul class="link-list">
                           <li><strong>Giorno 7:</strong> <a href="https://grovearcade.com/" target="_blank">Grove Arcade</a></li>
                           <li><strong>Giorno 9:</strong> <a href="https://www.boonehallplantation.com/" target="_blank">Boone Hall Plantation</a></li>
                           <li><strong>Giorno 11:</strong> <a href="https://www.jekyllisland.com/" target="_blank">Jekyll Island</a></li>
                           <li><strong>Giorno 12:</strong> <a href="https://www.alligatorfarm.com/" target="_blank">St. Augustine Alligator Farm</a></li>
                           <li><strong>Giorno 12:</strong> <a href="https://www.nps.gov/casa/index.htm" target="_blank">Castillo de San Marcos</a></li>
                           <li><strong>Giorno 12:</strong> <a href="https://www.flagler.edu/" target="_blank">Flagler College</a></li>
                           <li><strong>Giorno 13:</strong> <a href="https://www.kennedyspacecenter.com/" target="_blank">Kennedy Space Center</a></li>
                        </ul>
                         <h3 class="text-xl font-bold text-stone-800 mt-6 mb-3">Hotel Consigliati</h3>
                        <ul class="link-list">
                            <li><strong>Giorno 6:</strong> <a href="https://themontevistahotel.net/" target="_blank">The Monte Vista Hotel</a></li>
                            <li><strong>Giorno 6:</strong> <a href="https://www.redrockerinn.com/" target="_blank">Red Rocker Inn</a></li>
                            <li><strong>Giorno 8:</strong> <a href="https://www.hyatt.com/en-US/hotel/south-carolina/hyatt-house-charleston-mount-pleasant/chscm" target="_blank">Hyatt House Charleston/Mount Pleasant</a></li>
                            <li><strong>Giorno 8:</strong> <a href="https://thepalmsiop.com/" target="_blank">The Palms Oceanfront Hotel</a></li>
                            <li><strong>Giorno 9:</strong> <a href="https://www.thedesotosavannah.com/" target="_blank">The DeSoto Savannah</a></li>
                            <li><strong>Giorno 9:</strong> <a href="https://www.hyatt.com/en-US/hotel/georgia/andaz-savannah/savan" target="_blank">Andaz Savannah</a></li>
                            <li><strong>Giorno 9:</strong> <a href="https://www.hilton.com/en/hotels/savhdes-embassy-suites-savannah/" target="_blank">Embassy Suites by Hilton Savannah</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-stone-800 mb-3">Ristoranti e Locali</h3>
                        <ul class="link-list">
                            <li><strong>Giorno 7:</strong> <a href="https://farmburger.com/asheville-nc/" target="_blank">Farm Burger</a></li>
                            <li><strong>Giorno 8:</strong> <a href="https://www.crackerbarrel.com/" target="_blank">Cracker Barrel</a></li>
                            <li><strong>Giorno 8:</strong> <a href="https://vickerysmtpleasant.com/" target="_blank">Vickery's Bar & Grill</a></li>
                            <li><strong>Giorno 9:</strong> <a href="https://www.pagesokragrill.com/" target="_blank">Page's Okra Grill</a></li>
                            <li><strong>Giorno 10:</strong> <a href="https://savannahscandy.com/" target="_blank">Savannah's Candy Kitchen</a></li>
                            <li><strong>Giorno 10:</strong> <a href="https://mrswilkes.com/" target="_blank">Mrs. Wilkes' Dining Room</a></li>
                            <li><strong>Giorno 10:</strong> <a href="https://www.leopoldsicecream.com/" target="_blank">Leopold's Ice Cream</a></li>
                            <li><strong>Giorno 11:</strong> <a href="https://www.tortugajacks.com/" target="_blank">Tortuga Jack's</a></li>
                            <li><strong>Giorno 12:</strong> <a href="https://saltlifefoodshack.com/st-augustine/" target="_blank">Salt Life Food Shack</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sezione Orlando -->
            <div>
                <h2 class="text-3xl font-bold text-purple-600 border-b-2 border-purple-200 pb-2 mb-6">Parte 3: I Parchi a Tema di Orlando</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <h3 class="text-xl font-bold text-stone-800 mb-3">Parchi e Attrazioni</h3>
                        <ul class="link-list">
                            <li><strong>Giorno 14:</strong> <a href="https://www.universalorlando.com/web/en/us" target="_blank">Universal Orlando Resort</a></li>
                            <li><strong>Giorno 14:</strong> <a href="https://www.universalorlando.com/web/en/us/things-to-do/dining/citywalk-dining" target="_blank">Universal CityWalk</a></li>
                            <li><strong>Giorno 18:</strong> <a href="https://www.disneysprings.com/" target="_blank">Disney Springs</a></li>
                            <li><strong>Giorno 18:</strong> <a href="https://www.universalorlando.com/web/en/us/theme-parks/volcano-bay" target="_blank">Volcano Bay</a></li>
                            <li><strong>Giorno 19:</strong> <a href="https://disneyworld.disney.go.com/destinations/hollywood-studios/" target="_blank">Disney's Hollywood Studios</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>
</html>
