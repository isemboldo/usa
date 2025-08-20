<?php
// Definiamo la costante di sicurezza per includere file in futuro se necessario
define('ABSPATH', true);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mappa del Viaggio - Guida USA 2026</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Leaflet.js (per la mappa interattiva) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <link rel="stylesheet" href="/usa/assets/css/style.css">
    <style>
        #travelMap { height: 80vh; border-radius: 0.75rem; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1); }
        .leaflet-popup-content-wrapper { border-radius: 0.5rem; }
        .leaflet-popup-content { font-family: 'Inter', sans-serif; }
        .leaflet-popup-content a { font-weight: bold; color: #7c3aed; }
    </style>
</head>
<body>

    <header class="main-header">
        <div class="main-container flex justify-between items-center h-16">
            <a href="/usa/" class="text-xl font-bold text-stone-900 hover:text-purple-600">Viaggio USA 2026</a>
            <nav class="hidden md:flex items-center space-x-2">
                <a href="/usa/page/hub.php?sezione=new-york" class="nav-link">New York</a>
                <a href="/usa/page/hub.php?sezione=il-sud" class="nav-link">Il Sud</a>
                <a href="/usa/page/hub.php?sezione=orlando" class="nav-link">Orlando</a>
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
            <a href="/usa/page/link.php" class="block py-2 px-4 text-sm text-stone-700 hover:bg-stone-200">Link Utili</a>
        </div>
    </header>

    <main class="main-container py-12">
        <div class="text-center mb-8">
             <h1 class="text-4xl md:text-5xl font-bold">La Mappa del Nostro Viaggio</h1>
             <p class="mt-4 text-lg text-stone-600">Esplora le tappe principali della nostra avventura sulla East Coast.</p>
        </div>

        <div id="travelMap"></div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script per il menù mobile
            document.getElementById('mobile-menu-button').addEventListener('click', function() {
                document.getElementById('mobile-menu').classList.toggle('hidden');
            });

            // Inizializzazione della mappa
            const map = L.map('travelMap').setView([35.5, -79.5], 5); // Centrata sulla East Coast

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Dati delle tappe
            const tappe = [
                { lat: 40.7128, lng: -74.0060, nome: 'New York', slug: 'new-york' },
                { lat: 35.5951, lng: -82.5515, nome: 'Asheville / Black Mountain', slug: 'il-sud' },
                { lat: 32.7765, lng: -79.9311, nome: 'Charleston / Mount Pleasant', slug: 'il-sud' },
                { lat: 32.0809, lng: -81.0912, nome: 'Savannah', slug: 'il-sud' },
                { lat: 29.9047, lng: -81.3125, nome: 'St. Augustine', slug: 'il-sud' },
                { lat: 28.5383, lng: -81.3792, nome: 'Orlando', slug: 'orlando' }
            ];

            // Aggiunta dei marker
            tappe.forEach(tappa => {
                const marker = L.marker([tappa.lat, tappa.lng]).addTo(map);
                marker.bindPopup(`<b>${tappa.nome}</b><br><a href="/usa/page/hub.php?sezione=${tappa.slug}">Vai alla sezione &rarr;</a>`);
            });
        });
    </script>
</body>
</html>
