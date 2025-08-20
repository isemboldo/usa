<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rendiconto Viaggio USA 2026</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/usa/assets/css/style.css">
    
    <!-- Script di React -->
    <script src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <!-- Babel per compilare JSX nel browser -->
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
</head>
<body class="bg-stone-100">

    <header class="main-header">
        <div class="main-container flex justify-between items-center h-16">
            <a href="/usa/" class="text-xl font-bold text-stone-900 hover:text-purple-600">Viaggio USA 2026</a>
            <nav class="hidden md:flex items-center space-x-2">
                <a href="/usa/page/hub.php?sezione=new-york" class="nav-link">New York</a>
                <a href="/usa/page/hub.php?sezione=il-sud" class="nav-link">Il Sud</a>
                <a href="/usa/page/hub.php?sezione=orlando" class="nav-link">Orlando</a>
                <a href="/usa/map/index.php" class="nav-link">Mappa</a>
                <a href="/usa/page/link.php" class="nav-link">Link Utili</a>
            </nav>
            <!-- Aggiungeremo un menu mobile qui se necessario -->
        </div>
    </header>

    <!-- Contenitore dove verrà montata l'app React -->
    <div id="root"></div>

    <script type="text/babel">
        const { useState, useEffect } = React;

        // Componente per la barra di progresso
        const ProgressBar = ({ value, total }) => {
            const percentage = total > 0 ? (value / total) * 100 : 0;
            return (
                <div className="w-full progress-bar-bg mt-1">
                    <div className="progress-bar-fill" style={{ width: `${Math.min(100, percentage)}%` }}></div>
                </div>
            );
        };
        
        // Componente Accordion per i dettagli delle spese (secondo livello)
        const AccordionItem = ({ categoria, costi }) => {
            const [isOpen, setIsOpen] = useState(false);
            const totalCategoria = costi.reduce((sum, costo) => sum + costo.quota, 0);

            return (
                <div className="border-b border-stone-200 last:border-b-0">
                    <button 
                        className="w-full flex justify-between items-center py-3 text-left font-semibold text-stone-700 hover:text-purple-600"
                        onClick={() => setIsOpen(!isOpen)}
                    >
                        <span>{categoria}: {totalCategoria.toLocaleString('it-CH', { style: 'currency', currency: 'CHF' })}</span>
                        <span className={`transform transition-transform duration-300 ${isOpen ? 'rotate-180' : ''}`}>▼</span>
                    </button>
                    <div 
                        className="overflow-hidden transition-all duration-300 ease-in-out"
                        style={{ maxHeight: isOpen ? '1000px' : '0' }}
                    >
                        <div className="pb-3 pl-4">
                            <ul className="list-disc list-inside text-sm text-stone-600 space-y-1">
                                {costi.map((costo, index) => (
                                    <li key={index}>
                                        {costo.descrizione}: <em>{costo.quota.toLocaleString('it-CH', { style: 'currency', currency: 'CHF' })}</em>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    </div>
                </div>
            );
        };

        // Componente Card per ogni partecipante
        const ParticipantCard = ({ data }) => {
            const [isDetailsOpen, setIsDetailsOpen] = useState(false);

            return (
                <div className="bg-white rounded-xl shadow-lg p-6 flex flex-col">
                    <h3 className="text-2xl font-bold text-center mb-4">{data.nome}</h3>
                    <div className="space-y-4 flex-grow">
                        <div>
                            <div className="flex justify-between text-sm font-semibold text-stone-500">
                                <span>Totale Dovuto</span>
                                <span>{data.totale_dovuto.toLocaleString('it-CH', { style: 'currency', currency: 'CHF' })}</span>
                            </div>
                        </div>
                        <div>
                            <div className="flex justify-between text-sm font-semibold text-stone-500">
                                <span>Acconti Versati</span>
                                <span className="text-green-600">{data.acconti_versati.toLocaleString('it-CH', { style: 'currency', currency: 'CHF' })}</span>
                            </div>
                            <ProgressBar value={data.acconti_versati} total={data.totale_dovuto} />
                        </div>
                        <div className="pt-3 border-t">
                            <div className="flex justify-between text-lg font-bold text-stone-800">
                                <span>Saldo da Versare</span>
                                <span className="text-red-600">{data.saldo.toLocaleString('it-CH', { style: 'currency', currency: 'CHF' })}</span>
                            </div>
                        </div>
                    </div>
                    <div className="border-t mt-4 pt-2">
                        <button 
                            className="w-full flex justify-between items-center py-2 text-left font-bold text-purple-600 hover:text-purple-800"
                            onClick={() => setIsDetailsOpen(!isDetailsOpen)}
                        >
                            <span>Dettaglio Spese</span>
                            <span className={`transform transition-transform duration-300 ${isDetailsOpen ? 'rotate-180' : ''}`}>▼</span>
                        </button>
                        <div
                            className="overflow-hidden transition-all duration-500 ease-in-out"
                            style={{ maxHeight: isDetailsOpen ? '1000px' : '0' }}
                        >
                            <div className="pt-2">
                                {Object.keys(data.dettaglio_spese).length > 0 ? (
                                    Object.keys(data.dettaglio_spese).map(categoria => (
                                        <AccordionItem key={categoria} categoria={categoria} costi={data.dettaglio_spese[categoria]} />
                                    ))
                                ) : (
                                    <p className="text-sm text-stone-500 py-2">Nessuna spesa assegnata.</p>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            );
        };

        // Componente Principale dell'App
        const App = () => {
            const [budgetData, setBudgetData] = useState(null);
            const [loading, setLoading] = useState(true);
            const [error, setError] = useState(null);

            useEffect(() => {
                const fetchData = async () => {
                    try {
                        const response = await fetch('/usa/api/get_budget_data.php');
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        const data = await response.json();
                        setBudgetData(data);
                    } catch (e) {
                        setError(e.message);
                    } finally {
                        setLoading(false);
                    }
                };

                fetchData();
            }, []);

            if (loading) {
                return <div className="text-center p-12">Caricamento dei dati in corso...</div>;
            }

            if (error) {
                return <div className="text-center p-12 text-red-600">Errore: {error}</div>;
            }

            const { stato_budget, rendiconto_personale } = budgetData;

            return (
                <main className="main-container py-12">
                    <div className="text-center mb-12">
                         <h1 className="text-4xl md:text-5xl font-bold">Rendiconto del Nostro Viaggio</h1>
                         <p className="mt-4 text-lg text-stone-600">Una visione chiara e trasparente dei costi previsti e delle quote individuali.</p>
                    </div>

                    <section className="mb-8 bg-white p-6 rounded-xl shadow-lg">
                        <h2 className="text-3xl font-bold text-stone-900 mb-4">Stato del Budget</h2>
                        <div className="text-center">
                            <p className="text-lg text-stone-600">Il costo totale previsto del viaggio è di</p>
                            <p className="text-3xl sm:text-4xl md:text-5xl font-bold text-purple-600 my-2">
                                {stato_budget.costo_totale_previsto.toLocaleString('it-CH', { style: 'currency', currency: 'CHF' })}
                            </p>
                            <p className={stato_budget.scostamento > 0 ? 'text-red-600 font-semibold' : 'text-green-600 font-semibold'}>
                                {stato_budget.scostamento > 0 ? 'Stiamo spendendo ' : 'Stiamo risparmiando '}
                                {Math.abs(stato_budget.scostamento).toLocaleString('it-CH', { style: 'currency', currency: 'CHF' })}
                                {' rispetto alla stima iniziale.'}
                            </p>
                            <p className="text-xs text-stone-400 mt-2">
                                (Tassi di cambio applicati: 1 USD = {stato_budget.tassi_cambio.USD} CHF, 1 EUR = {stato_budget.tassi_cambio.EUR} CHF)
                            </p>
                        </div>
                    </section>

                    <section>
                        <h2 className="text-3xl font-bold text-stone-900 mb-4">Quote Individuali</h2>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {rendiconto_personale.map(persona => (
                                <ParticipantCard key={persona.nome} data={persona} />
                            ))}
                        </div>
                    </section>
                </main>
            );
        };

        ReactDOM.render(<App />, document.getElementById('root'));
    </script>

</body>
</html>
