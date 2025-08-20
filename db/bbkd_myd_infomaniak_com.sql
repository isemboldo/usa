-- phpMyAdmin SQL Dump
-- version 4.9.6
-- https://www.phpmyadmin.net/
--
-- Host: bbkd.myd.infomaniak.com
-- Creato il: Ago 19, 2025 alle 21:02
-- Versione del server: 10.6.18-MariaDB-deb11-log
-- Versione PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bbkd_viaggio_usa`
--
CREATE DATABASE IF NOT EXISTS `bbkd_viaggio_usa` DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci;
USE `bbkd_viaggio_usa`;

-- --------------------------------------------------------

--
-- Struttura della tabella `feedback_sezioni`
--

CREATE TABLE `feedback_sezioni` (
  `sezione_id` int(11) NOT NULL,
  `likes` int(11) NOT NULL DEFAULT 0,
  `dislikes` int(11) NOT NULL DEFAULT 0,
  `more_info` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `feedback_sezioni`
--

INSERT INTO `feedback_sezioni` (`sezione_id`, `likes`, `dislikes`, `more_info`) VALUES
(1, 0, 0, 0),
(2, 0, 0, 0),
(3, 0, 0, 0),
(4, 0, 0, 0),
(5, 0, 0, 0),
(6, 0, 0, 0),
(7, 0, 0, 0),
(8, 0, 0, 0),
(9, 0, 0, 0),
(10, 0, 0, 0),
(12, 0, 0, 0),
(13, 0, 0, 0),
(14, 0, 0, 0),
(15, 0, 0, 0),
(16, 0, 0, 0),
(17, 0, 0, 0),
(18, 0, 0, 0),
(19, 0, 0, 0),
(20, 0, 0, 0),
(21, 0, 0, 0),
(22, 0, 0, 0),
(23, 0, 0, 0),
(24, 0, 0, 0),
(25, 0, 0, 0),
(26, 0, 0, 0),
(27, 0, 0, 0),
(28, 0, 0, 0),
(29, 0, 0, 0),
(30, 0, 0, 0),
(31, 0, 0, 0),
(32, 0, 0, 0),
(33, 0, 0, 0),
(34, 0, 0, 0),
(35, 0, 0, 0),
(36, 0, 0, 0),
(37, 0, 0, 0),
(38, 0, 0, 0),
(39, 0, 0, 0),
(40, 0, 0, 0),
(41, 0, 0, 0),
(42, 0, 0, 0),
(43, 0, 0, 0),
(46, 0, 0, 0),
(47, 0, 0, 0),
(48, 0, 0, 0),
(49, 0, 0, 0),
(50, 0, 0, 0),
(51, 0, 0, 0),
(53, 0, 0, 0),
(54, 0, 0, 0),
(55, 0, 0, 0),
(56, 0, 0, 0),
(57, 0, 0, 0),
(58, 0, 0, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `giorni`
--

CREATE TABLE `giorni` (
  `id` int(11) NOT NULL,
  `parte_id` int(11) NOT NULL,
  `giorno_num` int(11) NOT NULL,
  `data` varchar(100) NOT NULL,
  `titolo` varchar(255) NOT NULL,
  `immagine_copertina` varchar(255) NOT NULL,
  `riassunto` text NOT NULL,
  `data_modifica` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `giorni`
--

INSERT INTO `giorni` (`id`, `parte_id`, `giorno_num`, `data`, `titolo`, `immagine_copertina`, `riassunto`, `data_modifica`) VALUES
(1, 1, 1, 'Mercoledì 29 luglio 2026', 'L\'Inizio dell\'Avventura: da Lugano a Manhattan', '/usa/assets/images/giorno-1.jpg', 'Partenza da Milano e arrivo a New York. Dopo il trasferimento a Manhattan e il check-in in hotel, ci aspetta una prima serata di relax per combattere il fuso orario e assaporare l\'atmosfera della città.', '2025-08-17 15:46:41'),
(2, 1, 2, 'Giovedì 30 luglio 2026', 'Il Cuore Pulsante di Midtown Manhattan', '/usa/assets/images/giorno-2.jpg', 'Mattinata tra Times Square e un giro in bici a Central Park. Pomeriggio dedicato allo shopping sulla 5th Avenue e conclusione con la vista mozzafiato dal Top of the Rock al tramonto.', '2025-08-17 15:38:06'),
(3, 1, 3, 'Venerdì 31 luglio 2026', 'Gusto, Arte e Parchi Urbani', '/usa/assets/images/giorno-3.jpg', 'Esplorazione del Greenwich Village, pranzo iconico da Katz\'s, e passeggiata sulla High Line. La giornata finisce al Chelsea Market e con un tramonto magico a Little Island.', '2025-08-10 17:05:06'),
(4, 1, 4, 'Sabato 1 agosto 2026', 'L\'Anima di Harlem e il Fascino di Coney Island', '/usa/assets/images/giorno-4.jpg', 'Mattinata culturale ad Harlem con un autentico pranzo \"Soul Food\", seguita da un pomeriggio di divertimento vintage a Coney Island, tra giostre storiche, spiaggia e l\'iconico hot dog di Nathan\'s.', '2025-08-10 17:07:38'),
(5, 1, 5, 'Domenica 2 agosto 2026', 'Storia, Memoria e Panorami a Lower Manhattan', '/usa/assets/images/giorno-5.jpg', 'Visita al 9/11 Memorial e al Financial District, seguita da un suggestivo viaggio in traghetto fino a DUMBO per ammirare il tramonto e cenare con una vista mozzafiato sullo skyline.', '2025-08-10 17:07:34'),
(6, 2, 6, 'Lunedì 3 agosto 2026', 'Dalle Montagne al Fascino di un \"Mountain Town\"', '/usa/assets/images/giorno-6.jpg', 'Volo da New York ad Asheville e ritiro dell\'auto. Arrivo nell\'incantevole paesino di Black Mountain per una serata di relax e un assaggio dell\'autentica atmosfera montana.', '2025-08-10 17:05:06'),
(7, 2, 7, 'Martedì 4 agosto 2026', 'Esplorazione di Asheville e della Blue Ridge Parkway', '/usa/assets/images/giorno-7.jpg', 'Mattinata alla scoperta del centro artistico di Asheville. Pomeriggio di guida panoramica sulla Blue Ridge Parkway, con soste per ammirare cascate e viste mozzafiato.', '2025-08-10 17:05:06'),
(8, 2, 8, 'Mercoledì 5 agosto 2026', 'Viaggio verso la Costa', '/usa/assets/images/giorno-8.jpg', 'Lunga ma affascinante tappa di trasferimento dalle montagne alla costa della South Carolina. Arrivo a Mount Pleasant e pomeriggio di puro relax in spiaggia o in piscina.', '2025-08-10 17:05:06'),
(9, 2, 9, 'Giovedì 6 agosto 2026', 'Storia del Sud e Arrivo a Savannah', '/usa/assets/images/giorno-9.jpg', 'Mattinata dedicata alla visita della storica Boone Hall Plantation, con il suo viale di querce e la toccante storia della cultura Gullah. Pomeriggio in viaggio verso Savannah.', '2025-08-10 17:05:06'),
(10, 2, 10, 'Venerdì 7 agosto 2026', 'Il Fascino Gotico di Savannah', '/usa/assets/images/giorno-10.jpg', 'Intera giornata a piedi per esplorare le 22 piazze storiche, River Street e il magnifico Forsyth Park. Pranzo-evento da Mrs. Wilkes\' Dining Room per un\'autentica abbuffata del Sud.', '2025-08-10 17:05:06'),
(11, 2, 11, 'Sabato 8 agosto 2026', 'Rotta verso la Florida con Sosta a Jekyll Island', '/usa/assets/images/giorno-11.jpg', 'Partenza da Savannah con una sosta a Jekyll Island per ammirare la surreale Driftwood Beach. Arrivo nel pomeriggio a St. Augustine, la città più antica degli USA.', '2025-08-10 17:05:07'),
(12, 2, 12, 'Domenica 9 agosto 2026', 'Alligatori e Conquistadores', '/usa/assets/images/giorno-12.jpg', 'Mattinata all\'Alligator Farm per un incontro ravvicinato con la fauna della Florida. Pomeriggio dedicato alla storia, tra il Castillo de San Marcos e il relax a St. Augustine Beach.', '2025-08-10 17:05:07'),
(13, 2, 13, 'Lunedì 10 agosto 2026', 'Missione Spaziale', '/usa/assets/images/giorno-13.jpg', 'Giornata dedicata alla conquista dello spazio con la visita al Kennedy Space Center a Cape Canaveral. In serata, arrivo a Orlando, pronti per la prossima fase dell\'avventura.', '2025-08-10 17:05:07'),
(14, 3, 14, 'Martedì 11 agosto 2026', 'Inizio Avventura a Orlando', '/usa/assets/images/giorno-14.jpg', 'Mattinata di saluti: due partecipanti rientrano in Italia. Il resto del gruppo si trasferisce in un hotel del Universal Orlando Resort per un pomeriggio di relax in piscina.', '2025-08-10 17:05:07'),
(15, 3, 15, 'Mercoledì 12 agosto 2026', 'Magia a Universal Studios', '/usa/assets/images/giorno-15.jpg', 'Prima giornata di parchi a tema, dedicata interamente a Universal Studios Florida. Esplorazione di Diagon Alley e delle altre attrazioni cinematografiche.', '2025-08-10 17:05:07'),
(16, 3, 16, 'Giovedì 13 agosto 2026', 'Avventura a Islands of Adventure', '/usa/assets/images/giorno-16.jpg', 'Seconda giornata dedicata a Islands of Adventure, tra le montagne russe di Jurassic Park, il mondo di Hogsmeade e le attrazioni adrenaliniche.', '2025-08-10 17:05:07'),
(17, 3, 17, 'Venerdì 14 agosto 2026', 'Scoperta di Epic Universe', '/usa/assets/images/giorno-17.jpg', 'Giornata dedicata all\'esplorazione del nuovissimo parco a tema Universal\'s Epic Universe, con i suoi mondi dedicati a Super Mario e ai mostri classici.', '2025-08-10 17:05:07'),
(18, 3, 18, 'Sabato 15 agosto 2026', 'Giorno di Relax', '/usa/assets/images/giorno-18.jpg', 'Una meritata pausa dai parchi. Giornata libera da trascorrere in piscina, a fare shopping a Disney Springs o visitando il parco acquatico Volcano Bay.', '2025-08-10 17:05:07'),
(19, 3, 19, 'Domenica 16 agosto 2026', 'Missione Star Wars', '/usa/assets/images/giorno-19.jpg', 'Immersione totale nell\'universo di Star Wars ai Disney\'s Hollywood Studios. L\'obiettivo è l\'area Galaxy\'s Edge, con le sue attrazioni e la sua atmosfera unica.', '2025-08-10 17:05:07'),
(20, 3, 20, 'Lunedì 17 agosto 2026', 'Rientro a casa', '/usa/assets/images/giorno-20.jpg', 'Mattinata libera per preparare i bagagli, check-out e trasferimento in aeroporto per il volo di rientro. Arrivo a Milano il giorno successivo e ritorno a Lugano.', '2025-08-10 17:05:07');

-- --------------------------------------------------------

--
-- Struttura della tabella `iscritti`
--

CREATE TABLE `iscritti` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `data_iscrizione` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `iscritti`
--

INSERT INTO `iscritti` (`id`, `nome`, `email`, `data_iscrizione`) VALUES
(1, 'Federico', 'iso@isohome.ch', '2025-08-07 20:25:51'),
(4, 'Paola', 'paola.iseppi@isohome.ch', '2025-08-08 17:38:20'),
(5, 'Franzi', 'francesca.iseppi@gmail.com', '2025-08-11 15:34:00'),
(6, 'Clarissa', 'clarissa.iseppi@morbidevoci.ch', '2025-08-11 15:35:30'),
(7, 'Emilio', 'iseppiemilio@bluewin.ch', '2025-08-12 16:53:47');

-- --------------------------------------------------------

--
-- Struttura della tabella `luoghi`
--

CREATE TABLE `luoghi` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `luoghi`
--

INSERT INTO `luoghi` (`id`, `nome`) VALUES
(3, 'Asheville'),
(2, 'Black Mountain'),
(8, 'Cape Canaveral'),
(6, 'Jekyll Island'),
(4, 'Mount Pleasant'),
(1, 'New York'),
(9, 'Orlando'),
(5, 'Savannah'),
(7, 'St. Augustine');

-- --------------------------------------------------------

--
-- Struttura della tabella `pagamenti`
--

CREATE TABLE `pagamenti` (
  `id` int(11) NOT NULL,
  `partecipante` varchar(100) NOT NULL,
  `importo` decimal(10,2) NOT NULL,
  `valuta` varchar(3) NOT NULL DEFAULT 'CHF',
  `data_pagamento` date NOT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `parti`
--

CREATE TABLE `parti` (
  `id` int(11) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `titolo` varchar(255) NOT NULL,
  `descrizione` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `parti`
--

INSERT INTO `parti` (`id`, `slug`, `titolo`, `descrizione`) VALUES
(1, 'new-york', 'Esplorando New York, tra icone, arte e sapori unici', 'Cinque giorni per assorbire l\'energia della città che non dorme mai. Dalle luci accecanti di Times Square all\'anima bohémien del Village, un\'immersione totale nel cuore pulsante del mondo, tra icone del cinema, sapori unici e storie indimenticabili.'),
(2, 'il-sud', 'Il fascino del Sud: un viaggio tra storia e natura', 'Un viaggio on the road che scende dalle vette fumose delle Blue Ridge Mountains fino alle coste coloniali del Sud. Un\'avventura tra natura selvaggia, storia profonda, sapori indimenticabili e la conquista dello spazio.'),
(3, 'orlando', 'Orlando: un tuffo nell\'avventura e nella fantasia', 'La conclusione perfetta tra magia e adrenalina. Una settimana immersi nei mondi fantastici dei parchi a tema, dove i sogni diventano realtà e l\'avventura è dietro ogni angolo.');

-- --------------------------------------------------------

--
-- Struttura della tabella `sezioni`
--

CREATE TABLE `sezioni` (
  `id` int(11) NOT NULL,
  `giorno_id` int(11) NOT NULL,
  `ordine` int(11) NOT NULL DEFAULT 0,
  `sovratitolo` varchar(255) DEFAULT NULL,
  `titolo` varchar(255) NOT NULL,
  `testo` text NOT NULL,
  `immagine` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `sezioni`
--

INSERT INTO `sezioni` (`id`, `giorno_id`, `ordine`, `sovratitolo`, `titolo`, `testo`, `immagine`) VALUES
(1, 1, 1, 'Mattina', 'La Partenza', '<p>L\'avventura inizia! Partenza in auto da Lugano. Il tragitto per <strong>Milano Malpensa (MXP)</strong> è di circa 1 ora, ma è cruciale partire con largo anticipo (almeno 3 ore prima del volo) per gestire con calma il traffico, specialmente in prossimità del confine e dell\'area di Milano, e per le procedure di parcheggio a lungo termine.</p>', '/usa/assets/images/giorno-1-mattina.jpg'),
(2, 1, 2, 'Pomeriggio', 'Il Volo Transatlantico', '<p>Imbarco sul vostro&nbsp;volo diretto per New York (JFK). Vi aspettano circa 8-9 ore di volo. Sarà l\'occasione perfetta per rilassarsi, guardare qualche film e iniziare ad abituarsi al fuso orario (-6 ore rispetto all\'Italia). Un consiglio per tutti: cercate di idratarvi bene e di dormire un po\' durante il volo per arrivare più riposati e pronti a combattere il jet lag.<br>&nbsp;</p>', '/usa/assets/images/giorno-1-pomeriggio.jpg'),
(3, 1, 3, 'Sera ', ' Benvenuti a New York!', '<p>Arrivo previsto nel tardo pomeriggio all\'aeroporto John F. Kennedy (JFK), uno degli hub aerei più trafficati del mondo, situato nel Queens. Procedure di Ingresso: All\'arrivo, affronterete le procedure di immigrazione statunitense. Un agente della CBP (Customs and Border Protection) vi farà alcune domande sul motivo del viaggio e sulla durata del soggiorno, e vi prenderà le impronte digitali e una foto. È una procedura standard, quindi mantenete la calma e rispondete con sincerità. Dopo l\'immigrazione, ritirerete i bagagli. Trasferimento a Manhattan: Per un gruppo di 6 persone con bagagli, l\'opzione più comoda ed efficiente è un van prenotato in anticipo (shuttle privato) o due taxi gialli ufficiali dalla banchina designata. Evitate gli autisti non autorizzati che potrebbero avvicinarvi all\'interno del terminal. Il viaggio verso Manhattan può durare da 45 a 90 minuti, a seconda del traffico. Check-in e Prima Serata: Una volta arrivati in hotel, effettuate il check-in. Per la prima sera, l\'obiettivo è resistere alla tentazione di andare a dormire subito. Fate una breve passeggiata nei dintorni dell\'hotel per sgranchirvi le gambe e cenate in un locale semplice e veloce. Una fetta di pizza newyorkese o un pasto in un \"diner\" americano sono perfetti per un primo, autentico assaggio della città.</p><p>Pomeriggio di relax nelle fantastiche piscine del resort. Per cena, immergetevi nell\'atmosfera vibrante di Universal CityWalk.</p>', '/usa/assets/images/giorno-1-sera.jpg'),
(4, 2, 1, 'Mattina (9:30 - 13:00)', 'Times Square e Central Park', '<p>Times Square: Iniziate la giornata con un\'immersione totale nell\'epicentro dell\'energia di New York. Una visita di 30-45 minuti è perfetta per scattare foto iconiche e assorbire l\'atmosfera unica. Central Park: Dopo il caos, cercate la tranquillità nel parco urbano più famoso del mondo. Noleggiate delle biciclette vicino a Columbus Circle per un tour di circa 2 ore. Itinerario in Bici Consigliato (circa 1.5 - 2 ore): Partendo da Columbus Circle, pedalate verso est fino al Conservatory Water per vedere le statue di Alice nel Paese delle Meraviglie. Scendete poi a sud verso la maestosa Bethesda Terrace e percorrete il viale alberato di The Mall. Infine, attraversate il parco verso ovest per una sosta a Strawberry Fields, il memoriale dedicato a John Lennon, prima di concludere il giro.</p>', '/usa/assets/images/giorno-2-mattina.jpg'),
(5, 2, 3, 'Pomeriggio (14:00 - 17:00)', 'Shopping sulla 5th Avenue', '<p>La Via dello Shopping: Dedicate il pomeriggio all\'esplorazione della 5th Avenue, una delle vie commerciali più famose al mondo. Partendo da Central Park South (vicino all\'Apple Store con il suo iconico cubo di vetro), potete scendere verso sud. Per Tutti i Gusti: Oltre alle boutique di lusso come Tiffany &amp; Co., Bergdorf Goodman e Saks Fifth Avenue (le cui vetrine sono un\'attrazione di per sé), troverete i grandi flagship store di marchi internazionali che piaceranno anche alle ragazze, come Uniqlo, Zara, e il grandissimo Nike Store,&nbsp;senza dimenticare lo Store NBA e NHL. Non dimenticate una tappa al negozio di giocattoli FAO Schwarz al Rockefeller Center.</p>', '/usa/assets/images/giorno-2-pomeriggio.jpg'),
(6, 2, 4, 'Tardo Pomeriggio (17:00 - 18:30)', 'Architettura e Panorama', '<p>Cattedrale di San Patrizio: Durante la vostra passeggiata sulla 5th Avenue, fermatevi ad ammirare la magnifica Cattedrale di San Patrizio. Il suo stile neogotico contrasta splendidamente con i grattacieli circostanti. Rockefeller Center e Top of the Rock: Concludete lo shopping al Rockefeller Center. Avvicinatevi all\'orario prenotato per la salita all\'osservatorio Top of the Rock. Consiglio strategico: Prenotate online i biglietti per una fascia oraria intorno alle 17:30. In questo modo, avrete il tempo di salire con calma e godervi la vista mozzafiato sulla città durante il tramonto e l\'accensione delle prime luci della sera.</p>', '/usa/assets/images/giorno-2-sera.jpg'),
(7, 3, 1, 'Mattina', 'Greenwich Village e Tenement Museum', '<p>Un itinerario a piedi che vi porter&agrave; dal West Village a Katz\'s</p>\r\n<ul>\r\n<li dir=\"ltr\" role=\"presentation\"><strong>Punto di Partenza - Washington Square Park</strong>: La giornata inizia nel cuore pulsante del Village. Ammirate il maestoso <strong>Washington Square Arch</strong> e la grande fontana, osservando la variegata umanit&agrave; che anima il parco: studenti della NYU, artisti di strada, musicisti e giocatori di scacchi.</li>\r\n<li dir=\"ltr\" role=\"presentation\"><strong>West Village e Storia LGBTQ+</strong>: Dal parco, dirigetevi a ovest verso <strong>Christopher Street</strong>. Qui, al numero 53, si trova lo <a href=\"https://thestonewallinnnyc.com/\" target=\"_blank\" rel=\"noopener\"><strong>Stonewall Inn</strong></a>, un Monumento Storico Nazionale. I moti dello Stonewall del 1969 sono considerati l\'evento che ha dato inizio al moderno movimento per i diritti LGBTQ+. &Egrave; un luogo di grande importanza culturale.</li>\r\n<li dir=\"ltr\" role=\"presentation\"><strong>Angoli Nascosti e Set Cinematografici</strong>: Esplorate le strade tranquille e affascinanti del West Village. All\'angolo tra <strong>Grove Street e Bedford Street</strong> troverete l\'edificio utilizzato per le riprese esterne dell\'appartamento della serie TV \"Friends\", una tappa divertente per tutti. Poco lontano, al 75&frac12; di Bedford Street, cercate la casa pi&ugrave; stretta di New York!</li>\r\n<li dir=\"ltr\" role=\"presentation\"><strong>Cuore Boh&eacute;mien e Musicale</strong>: Tornate verso est e imboccate <strong>MacDougal Street</strong>. Qui, al numero 113, concedetevi una sosta al <a href=\"https://www.caffereggio.com/\" target=\"_blank\" rel=\"noopener\"><strong>Caffe Reggio</strong></a>. Questo caff&egrave; storico, che si vanta di aver servito il primo cappuccino negli Stati Uniti nel 1927, ha un\'atmosfera unica, con arredi antichi e opere d\'arte rinascimentali. &Egrave; il posto perfetto per una pausa caff&egrave; e per respirare la storia boh&eacute;mien del quartiere. Proprio accanto si trova il famoso <strong>Comedy Cellar</strong>. Proseguite poi sulla mitica <strong>Bleecker Street</strong>, dove al numero 152 passerete davanti a <a href=\"https://bitterend.com/\" target=\"_blank\" rel=\"noopener\"><strong>The Bitter End</strong></a>, il pi&ugrave; antico rock club di NY, dove hanno suonato leggende come Bob Dylan e Lady Gaga.</li>\r\n<li dir=\"ltr\" role=\"presentation\"><strong>Verso il Lower East Side</strong> e il <a href=\"https://www.tenement.org/\" target=\"_blank\" rel=\"noopener\"><strong>Tenement Museum</strong></a>: Terminata la passeggiata su Bleecker Street, dirigetevi verso est, attraversando NoHo fino al <strong>Lower East Side</strong>. La vostra meta &egrave; il <a href=\"https://www.tenement.org/\" target=\"_blank\" rel=\"noopener\"><strong>Tenement Museum</strong></a> (103 Orchard Street). Questo non &egrave; un museo tradizionale, ma un\'esperienza immersiva che, tramite tour guidati, vi porta all\'interno degli appartamenti restaurati dove vissero vere famiglie di immigrati.</li>\r\n<li dir=\"ltr\" role=\"presentation\"><strong>Tour Consigliato</strong>: Per il vostro gruppo, il tour \"<strong>Under One Roof</strong>\" &egrave; ideale. Racconta le storie di tre famiglie diverse (ebrea, portoricana e cinese) che hanno vissuto nello stesso edificio in epoche diverse, offrendo uno spaccato affascinante e toccante sull\'evoluzione del quartiere e del Sogno Americano.</li>\r\n<li dir=\"ltr\" role=\"presentation\"><strong>Prenotazione (Fondamentale)</strong>: &Egrave; <strong>obbligatorio prenotare online i biglietti con largo anticipo</strong>, scegliendo il tour e l\'orario preciso. I posti sono limitati e si esauriscono in fretta. La visita dura circa 60-90 minuti.</li>\r\n</ul>', '/usa/assets/images/giorno-3-mattina.jpg'),
(8, 3, 2, 'Pranzo (13:30 - 15:00)', 'Un\'Istituzione Culinaria: Katz\'s', '<ul>\r\n<li dir=\"ltr\" aria-level=\"2\">\r\n<p dir=\"ltr\" role=\"presentation\"><a href=\"https://katzsdelicatessen.com/\" target=\"_blank\" rel=\"noopener\"><strong>Katz\'s Delicatessen</strong></a>: Usciti dal museo, vi troverete a pochi passi da Katz\'s, pronti per un\'esperienza culinaria autentica. Fondato nel 1888, &egrave; il pi&ugrave; famoso \"deli\" ebraico della citt&agrave;. All\'ingresso vi daranno un biglietto: non perdetelo! Mettetevi in fila davanti ai \"cutters\" (gli addetti al taglio) e ordinate direttamente da loro.</p>\r\n</li>\r\n<li dir=\"ltr\" aria-level=\"2\">\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Cosa ordinare</strong>: Il re indiscusso &egrave; il <strong>panino al pastrami</strong>, servito su pane di segale con senape. La carne, tenerissima e speziata, viene tagliata a mano davanti a voi. Per un\'esperienza completa, assaggiate anche i cetriolini sott\'aceto (\"pickles\").</p>\r\n</li>\r\n<li><strong>Curiosit&agrave; Culturale</strong>: Katz\'s &egrave; anche una star del cinema. &Egrave; qui che &egrave; stata girata la famosa scena del film \"Harry, ti presento Sally...\". Troverete un cartello che indica il tavolo esatto!</li>\r\n</ul>', '/usa/assets/images/giorno-3-pranzo.jpg'),
(9, 3, 3, 'Pomeriggio (15:30 - 17:30)', 'Passeggiata sulla High Line', '<ul>\r\n<li dir=\"ltr\" aria-level=\"2\">\r\n<p dir=\"ltr\" role=\"presentation\"><strong>La High Line</strong>: Dopo pranzo, prendete la metropolitana o un taxi per raggiungere l\'inizio della <a href=\"https://www.thehighline.org/\" target=\"_blank\" rel=\"noopener\"><strong>High Line</strong></a>. Questa ex linea ferroviaria sopraelevata &egrave; stata trasformata in un parco pubblico lungo circa 2,3 km. Passeggiare qui offre una prospettiva unica sulla citt&agrave;, tra giardini curati, installazioni d\'arte contemporanea e viste mozzafiato sui grattacieli e sul fiume Hudson.</p>\r\n</li>\r\n<li><strong>Percorso Consigliato</strong> (da Nord a Sud): Iniziate dall\'accesso nord, nella zona di <strong>Hudson Yards</strong> (vicino alla 34th Street). Questa &egrave; la parte pi&ugrave; nuova e scenografica, con la famosa struttura \"The Vessel\". Camminando verso sud, attraverserete il quartiere di Chelsea, passando sopra le sue strade trafficate. Scendete utilizzando l\'accesso sulla 16th Street, che vi lascer&agrave; a pochi passi</li>\r\n</ul>', '/usa/assets/images/giorno-3.jpg'),
(10, 4, 1, 'Mattina (10:00 - 13:00)', 'Lo Spirito di Harlem', '<p><strong>Inizio Monumentale a Morningside Heights</strong>.&nbsp;Inizia dalla <a href=\"https://www.stjohndivine.org/\"><strong>Cathedral of St. John the Divine</strong></a> (fermata metro Cathedral Pkwy - 110th St). Prendetevi tempo per ammirare l\'imponente facciata e, se possibile, sbirciare nella navata. Nelle vicinanze si trova anche il campus della <a href=\"https://www.columbia.edu/\"><strong>Columbia University</strong></a>, che merita uno sguardo.</p><p><strong>Transizione attraverso il Parco</strong>. Dal sagrato della cattedrale, costeggiate il <strong>Morningside Park</strong>, camminando verso nord in direzione della 125th Street. Questa passeggiata vi farà scendere dalle alture di Morningside Heights, per entrare nel vivo di Harlem.</p><p><strong>Il Cuore Pulsante: 125th Street</strong>. <a href=\"https://www.apollotheater.org/\"><strong>Apollo Theater</strong></a>. La vostra prima fermata sulla 125th Street è una leggenda. Fermatevi davanti all\'<strong>Apollo Theater</strong>. Racconta alle ragazze chi ha debuttato qui, da Ella Fitzgerald ai Jackson 5. È un pezzo di storia della musica mondiale.&nbsp;<br><strong>Studio Museum</strong>. Proseguendo sulla 125th, arrivate allo <a href=\"https://www.studiomuseum.org/\"><strong>Studio Museum</strong></a> in Harlem. Una visita di 30-40 minuti vi darà un assaggio significativo dell\'arte del quartiere.</p><p><strong>Architettura e Fede.</strong>&nbsp;Dallo Studio Museum, dirigetevi a nord verso la 138th Street. Qui potrete ammirare le splendide case di <strong>Striver\'s Row</strong>, l\'oasi di eleganza di fine Ottocento citata nel testo. Poco distante si trova anche la <strong>Abyssinian Baptist Church</strong>, famosa per il gospel. Anche se non potrete assistere al servizio, vederla dall\'esterno vi darà un\'idea della sua importanza nella comunità.</p><p>&nbsp;</p>', '/usa/assets/images/giorno-4-mattina.jpg'),
(12, 5, 4, 'Sera (17:00 in poi)', 'Tramonto e Cena a DUMBO', '<ul><li><strong>Tramonto a DUMBO:</strong> Sbarcati a DUMBO, arriverete giusto in tempo per il momento più magico. Posizionatevi su Washington Street per la celebre foto con il Manhattan Bridge e l\'Empire State Building, e poi godetevi lo spettacolo del sole che cala dietro lo skyline di Manhattan dal Main Street Park.<br>&nbsp;</li><li><strong>Cena:</strong> Cenate in uno dei famosi locali di DUMBO, come la pizzeria Grimaldi\'s o Juliana\'s Pizza, per concludere una giornata indimenticabile.</li></ul>', '/usa/assets/images/dumbo.jpg'),
(13, 4, 3, 'Pomeriggio (14:30 - Tramonto)', 'Viaggio nel Tempo a Coney Island', '<p><strong>Come arrivare</strong>: Dopo pranzo, prendete la metropolitana per un viaggio che vi porterà dal cuore culturale di Manhattan alla costa dell\'oceano. Il tragitto da Harlem a Coney Island è lungo (circa 1h 15m / 1h 30m), ma è un\'esperienza che vi mostrerà il volto mutevole della città.</p><p><br><strong>L\'Atmosfera</strong>: Benvenuti a <strong>Coney Island</strong>! L\'atmosfera è quella di un luna park d\'altri tempi, un po\' decadente e incredibilmente affascinante.</p><p><strong>Passeggiata sul Boardwalk</strong>: Percorrete la famosa passerella di legno, il cuore pulsante di Coney Island.</p><p><a href=\"https://lunaparknyc.com/\"><strong>Luna Park</strong></a>: Ammirate (o provate, se siete coraggiosi!) le giostre storiche come il Cyclone, le montagne russe in legno del 1927, e la Wonder Wheel, la ruota panoramica.</p><p><strong>Nathan\'s Famous</strong>: Non potete andarvene senza aver mangiato un hot dog da <a href=\"https://nathansfamous.com/\"><strong>Nathan\'s Famous</strong></a>, il locale originale del 1916 dove è nata la leggenda.</p><p><strong>Spiaggia</strong>: Godetevi la vasta spiaggia e, perché no, bagnate i piedi nell\'Oceano Atlantico.</p>', '/usa/assets/images/giorno-5-pomeriggio.jpg'),
(14, 6, 1, 'Mattina e Pomeriggio', 'Trasferimento', '<p dir=\"ltr\" role=\"presentation\"><strong>Addio a New York</strong>.&nbsp;Check-out dall\'hotel e trasferimento a JFK (partire almeno 4 ore prima del volo).</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Volo e Arrivo tra le Montagne</strong>.&nbsp;Volo Interno: Volo di circa <strong>2 ore</strong> da JFK ad Asheville (AVL).<br><strong>Arrivo e Ritiro Auto</strong>: Ritiro del vostro Minivan/SUV grande al piccolo e comodo aeroporto di Asheville.</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Tragitto in auto</strong>: Aeroporto di Asheville (AVL) &rarr; Black Mountain, NC</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Distanza</strong>: circa 35 km<br><strong>Tempo stimato</strong>: 35-40 minuti</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Check-in in Hotel</strong>: Arrivo a Black Mountain, un incantevole e autentico paese di montagna. Effettuate il check-in nel vostro alloggio.</p>\r\n<p>&nbsp;</p>', '/usa/assets/images/giorno-6-pomeriggio.jpg'),
(15, 6, 2, 'Sera', 'Serata a Black Mountain', '<p dir=\"ltr\" role=\"presentation\"><strong>L\'atmosfera</strong>: Dedicate la serata a esplorare a piedi il piccolo e delizioso centro di Black Mountain. &Egrave; pieno di negozi di artigianato, gallerie d\'arte e sedie a dondolo sui portici. L\'atmosfera &egrave; rilassata e autentica.</p>\r\n<p dir=\"ltr\" role=\"presentation\">Consigli Ristoranti:<br><strong>Pizza &amp; Birra Artigianale</strong>: <em>My Father\'s Pizza &amp; Pasta</em> - Un\'istituzione locale, amatissima da tutti, perfetta per una cena informale in famiglia.<br><strong>Cucina Locale</strong>: <em>The Trailhead Restaurant and Bar</em> - Offre un men&ugrave; vario con piatti americani e un\'atmosfera da rifugio di montagna.</p>', '/usa/assets/images/giorno-6.jpg'),
(16, 7, 1, 'Mattina (9:30 - 12:30)', 'Visita ad Asheville', '<p dir=\"ltr\" role=\"presentation\"><strong>Trasferimento</strong>: Dopo una colazione tranquilla a Black Mountain, prendete l\'auto per un breve tragitto.</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Tragitto</strong>: Black Mountain &rarr; Downtown Asheville<br><strong>Distanza</strong>: circa 25 km<br><strong>Tempo stimato</strong>: 20-25 minuti</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Passeggiata nel Downtown</strong>: Dedicate la mattinata a esplorare a piedi il centro di Asheville, da<strong> Pack Square</strong> al <a href=\"https://grovearcade.com/\" target=\"_blank\" rel=\"noopener\"><strong>Grove Arcade</strong></a>, curiosando tra le sue librerie e i suoi negozi unici.</p>', '/usa/assets/images/giorno-7-mattina.jpg'),
(17, 7, 3, 'Pomeriggio (13:30 - 18:00)', 'Avventura sulla Blue Ridge Parkway', '<p><strong>Prima Sosta: Mount Pisgah.</strong> &Egrave; una delle aree pi&ugrave; iconiche, perfetta per ammirare la vista mozzafiato direttamente dal parcheggio.<br><strong>Seconda Sosta: Looking Glass Rock Overlook.</strong> Un punto panoramico ideale per una foto a una delle formazioni rocciose pi&ugrave; famose della regione.<br><strong>Terza Sosta: Graveyard Fields.</strong> Qui farete una breve e facile escursione di circa 20-30 minuti per raggiungere le <strong>Second Falls</strong>, una bellissima cascata.</p>', '/usa/assets/images/giorno-7.jpg'),
(18, 8, 1, 'Mattina (8:30 - 13:00)', 'Il Viaggio verso la Costa', '<p dir=\"ltr\" role=\"presentation\"><strong>Partenza</strong>: Dopo colazione, partenza da Asheville. &Egrave; la tappa di trasferimento pi&ugrave; lunga del viaggio, ma attraversa paesaggi mutevoli, dalle montagne alle pianure costiere.</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Dettagli del Percorso</strong>:<br><strong>Tragitto</strong>: Asheville, NC &rarr; Mount Pleasant, SC<br><strong>Strade Principali</strong>: I-26 East</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Distanza</strong>: circa 440 km<br><strong>Tempo stimato</strong>: 4 ore e 30 minuti (senza traffico o soste)</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Sosta per il Pranzo</strong>: Un Classico Americano. A circa 2 ore e mezza da Asheville, poco prima di Columbia, troverete diverse uscite autostradali (es. Lexington/Irmo) con opzioni perfette per una sosta strategica. Cercate un <strong>Cracker Barrel Old Country Store</strong>. Non &egrave; solo un ristorante, ma un\'esperienza: offre cucina casalinga del Sud in un\'atmosfera rustica e ha un negozio annesso pieno di dolciumi, souvenir e oggetti curiosi. &Egrave; una tappa iconica dei viaggi on the road americani, perfetta per una pausa di circa un\'ora.</p>\r\n<p>&nbsp;</p>', '/usa/assets/images/giorno-8-mattina.jpg'),
(19, 8, 2, 'Pomeriggio (14:30 in poi)', 'Arrivo, Check-in e Relax al Mare', '<p dir=\"ltr\" role=\"presentation\"><strong>Arrivo e Check-in</strong>: Arriverete a Mount Pleasant, una piacevole cittadina suburbana alle porte di Charleston. Effettuate il check-in nel vostro hotel.</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Relax</strong>: Dopo il lungo viaggio, dedicate il resto del pomeriggio al relax. Se avete scelto un hotel a <strong>Isle of Palms</strong>, andate subito in spiaggia per un bagno rinfrescante nell\'oceano.</p>', '/usa/assets/images/giorno-8-pomeriggio.jpg'),
(20, 9, 1, 'Mattina', 'Visita a Boone Hall Plantation', '<p dir=\"ltr\" role=\"presentation\"><strong>Visita</strong>: Dopo una colazione tranquilla, dedicate la mattinata alla visita della storica <a href=\"https://boonehallplantation.com/\" target=\"_blank\" rel=\"noopener\">Boone Hall Plantation</a> (si trova a pochi minuti di auto da Mount Pleasant/Isle of Palms).</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Contesto Culturale</strong>: Boone Hall &egrave; una delle pi&ugrave; antiche piantagioni ancora in funzione d\'America (fondata nel 1681). &Egrave; un luogo di una bellezza struggente ma anche un\'importante testimonianza della storia della schiavit&ugrave; nel Sud.</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Cosa non perdere:<br>Avenue of Oaks</strong>: L\'iconico viale di querce secolari piantate nel 1743, famoso per essere apparso in film come \"Le Pagine della Nostra Vita\".<br><strong>House Tour</strong>: La visita guidata della casa padronale (ricostruita nel 1936) vi racconter&agrave; la storia delle famiglie che l\'hanno abitata.<br><strong>Slave Street e Gullah Culture</strong>: La parte pi&ugrave; importante e toccante. Le nove capanne originali degli schiavi ospitano mostre sulla loro vita. Non perdete la presentazione sulla cultura Gullah, tenuta dai discendenti diretti degli schiavi, che attraverso canti e racconti preservano la loro lingua e le loro tradizioni uniche. &Egrave; un\'esperienza educativa e commovente per tutti.</p>', '/usa/assets/images/giorno-9.jpg'),
(21, 9, 3, 'Pomeriggio (14:30 in poi)', 'Viaggio verso Savannah', '<p dir=\"ltr\" role=\"presentation\"><strong>Partenza</strong>: Nel primo pomeriggio, partenza per Savannah.</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Dettagli del Percorso</strong>:<br><strong>Tragitto</strong>: Mount Pleasant, SC &rarr; Savannah, GA<br><strong>Strade Principali</strong>: US-17 South<br><strong>Distanza</strong>: circa 170 km<br><strong>Tempo stimato</strong>: 2 ore</p>\r\n<p dir=\"ltr\" role=\"presentation\">Arrivo e Check-in: Arriverete a Savannah nel tardo pomeriggio. Effettuate il check-in nel vostro hotel nel <strong>centro storico</strong> e uscite per una prima passeggiata serale per assorbire l\'atmosfera unica della citt&agrave;.</p>', '/usa/assets/images/giorno-9-pomeriggio.jpg'),
(22, 10, 1, 'Mattina (9:30 - 12:00)', 'Esplorazione a Piedi del Quartiere Storico', '<p dir=\"ltr\" role=\"presentation\"><strong>Contesto Culturale</strong>: Il centro di Savannah &egrave; un capolavoro di urbanistica. Progettato nel 1733 da James Oglethorpe, il suo schema a griglia &egrave; interrotto da 22 piazze alberate, ognuna un piccolo parco con monumenti e panchine. &Egrave; questo che rende la citt&agrave; cos&igrave; piacevole da esplorare a piedi.</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Itinerario a Piedi</strong>:<br>Iniziate da <strong>Johnson Square</strong>, la pi&ugrave; antica e grande.<br>Dirigetevi verso il fiume, passando per <strong>Reynolds Square</strong>.<br>Scendete le storiche scalinate fino a <strong>River Street</strong>. Passeggiate lungo il fiume, ammirando le facciate dei vecchi magazzini di cotone, oggi negozi e ristoranti. Non perdete una sosta da <a href=\"https://savannahcandy.com/\" target=\"_blank\" rel=\"noopener\"><strong>Savannah\'s Candy Kitchen</strong></a> per assaggiare le \"pralines\", un dolce tipico</p>', '/usa/assets/images/giorno-10-mattina.jpg'),
(23, 10, 2, 'Pranzo (12:00 - 14:00)', 'L\'Esperienza da Mrs. Wilkes', '<p dir=\"ltr\" role=\"presentation\"><strong>L\'Avvicinamento</strong>: Da River Street, risalite verso sud e passeggiate lungo la meravigliosa <strong>Jones Street</strong>, considerata una delle strade residenziali pi&ugrave; belle d\'America.</p>\r\n<p dir=\"ltr\" role=\"presentation\"><a href=\"https://mrswilkes.com/\" target=\"_blank\" rel=\"noopener\"><strong>Mrs. Wilkes\' Dining Room</strong></a> (107 W Jones St): Arrivate qui intorno alle 11:00. Non &egrave; un ristorante tradizionale: non c\'&egrave; un\'insegna vistosa n&eacute; un men&ugrave;. Ci si mette in fila e si attende di essere chiamati per sedersi a grandi tavoli da 10 persone, insieme ad altri avventori.</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>L\'Esperienza</strong>: Una volta seduti, i tavoli vengono riempiti con decine di piatti della tradizione del Sud: pollo fritto, polpettone, maccheroni al formaggio, pur&egrave;, fagiolini, e molto altro. &Egrave; un\'abbuffata comunitaria, un\'esperienza sociale e culinaria indimenticabile.</p>', '/usa/assets/images/giorno-10-pranzo.jpg'),
(24, 10, 3, 'Pomeriggio (14:00 - 17:00)', 'Cattedrale e Parco', '<p dir=\"ltr\" role=\"presentation\"><strong>Passeggiata Digestiva</strong>: Dopo pranzo, una passeggiata &egrave; d\'obbligo. Dirigetevi verso la <strong>Cathedral of St. John the Baptist</strong>. La sua architettura neogotica francese e le sue vetrate colorate sono spettacolari.</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Forsyth Park</strong>: Concludete il vostro tour a piedi nel parco pi&ugrave; grande e famoso di Savannah. Ammirate l\'iconica fontana del XIX secolo, passeggiate sotto le querce secolari cariche di muschio spagnolo e rilassatevi sull\'erba.</p>', '/usa/assets/images/giorno-10.jpg'),
(25, 11, 1, 'Mattina (9:00 - 10:30)', ' Partenza e Viaggio verso le Golden Isles', '<p dir=\"ltr\" role=\"presentation\"><strong>Partenza</strong>: Dopo una colazione tranquilla, effettuate il check-out e partite da Savannah un po\' prima del previsto.</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Dettagli del Percorso</strong>:<br><strong>Tragitto</strong>: Savannah, GA &rarr; Jekyll Island, GA<br><strong>Strade Principali</strong>: I-95 South<br><strong>Distanza</strong>: circa 145 km<br><strong>Tempo stimato</strong>: 1 ora e 30 minuti</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Esplorazione di Jekyll Island</strong></p>\r\n<p dir=\"ltr\" role=\"presentation\">Benvenuti a <a href=\"https://www.jekyllisland.com/\" target=\"_blank\" rel=\"noopener\">Jekyll Island</a>, una delle \"Golden Isles\" della Georgia. Un tempo era il rifugio invernale esclusivo di alcune delle famiglie pi&ugrave; ricche d\'America (i Rockefeller, i Vanderbilt). Oggi &egrave; un parco statale protetto. <strong>Nota</strong>: C\'&egrave; una piccola tassa d\'ingresso per veicolo per accedere all\'isola.</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Driftwood Beach</strong>: La vostra meta principale. Dirigetevi verso l\'estremit&agrave; nord dell\'isola per ammirare questa spiaggia surreale e magnifica. L\'erosione ha fatto s&igrave; che la spiaggia sia disseminata di antiche querce e pini sbiancati dal sole e levigati dall\'acqua, creando un paesaggio quasi ultraterreno. &Egrave; un paradiso per i fotografi e un luogo affascinante da esplorare per tutte le et&agrave;.</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Pranzo</strong>: Pranzate sull\'isola. Potete optare per un picnic sulla spiaggia o scegliere uno dei locali informali come il <a href=\"https://www.tortugajacks.com/\" target=\"_blank\" rel=\"noopener\"><strong>Tortuga J</strong><strong>ack\'s</strong></a>,&nbsp;che offre cucina messicana con vista sull\'oceano.</p>', '/usa/assets/images/giorno-11.jpg'),
(26, 11, 2, 'Pomeriggio (14:00 in poi)', ' Viaggio finale verso St. Augustine', '<p dir=\"ltr\" role=\"presentation\"><strong>Dettagli del Percorso</strong>:<br><strong>Tragitto</strong>: Jekyll Island, GA &rarr; St. Augustine, FL<br><strong>Strade Principali</strong>: I-95 South<br><strong>Distanza</strong>: circa 120 km<br><strong>Tempo stimato</strong>: 1 ora e 20 minuti</p>\r\n<p dir=\"ltr\" role=\"presentation\">Arriverete a St. Augustine nel pomeriggio, con ancora molto tempo a disposizione. Effettuate il check-in nel vostro hotel.</p>\r\n<p><strong>Prima Esplorazione</strong>: Dedicate il resto del pomeriggio a un primo assaggio della citt&agrave;. Passeggiate lungo la pedonale <strong>St. George Street</strong>, il cuore pulsante del quartiere storico, piena di negozi, gallerie e locali. Ammirate dall\'esterno l\'imponente fortezza spagnola, il<strong> Castillo de San Marcos</strong>.</p>', '/usa/assets/images/giorno-11-sera.jpg'),
(27, 12, 1, 'Mattina (9:30 - 12:30)', 'Avventura all\'Alligator Farm', '<p dir=\"ltr\" role=\"presentation\"><a href=\"https://www.alligatorfarm.com/\" target=\"_blank\" rel=\"noopener\"><strong>St. Augustine Alligator Farm Zoological Park</strong></a>: Iniziate la giornata con una visita a una delle attrazioni pi&ugrave; antiche e famose della Florida (fondata nel 1893). Non &egrave; solo un \"allevamento\", ma un vero e proprio parco zoologico.</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Cosa vedere:</strong><br><strong>Land of Crocodiles</strong>: Qui potrete vedere tutte le 24 specie di coccodrilli esistenti al mondo, un\'impresa che nessun altro parco pu&ograve; vantare.<br><strong>Alligator Lagoon</strong>: Un\'area piena di centinaia di alligatori americani. Non perdetevi gli orari dei pasti (\"feeding times\"), uno spettacolo impressionante.<br><strong>Maximo</strong>: Incontrate Maximo, un gigantesco coccodrillo marino australiano di quasi 5 metri.<br><strong>Rookery</strong>: Per gli amanti della natura, la passerella che attraversa la palude (\"rookery\") offre la possibilit&agrave; di vedere da vicino aironi, spatole e altri uccelli acquatici che nidificano liberamente.<br><strong>Zip-line</strong>: Cimetatevi nella zipline che sorvola le vasche degli alligatori.</p>\r\n<p><strong>Consiglio</strong>: La visita dura circa 2-3 ore. Andare di mattina &egrave; ideale perch&eacute; gli animali sono pi&ugrave; attivi e il caldo &egrave; meno intenso.</p>', '/usa/assets/images/giorno-12-mattina.jpg'),
(28, 12, 3, 'Pomeriggio (14:00 - 17:00)', 'Esplorazione Storica', '<p dir=\"ltr\" role=\"presentation\"><a href=\"https://www.nps.gov/casa/index.htm\" target=\"_blank\" rel=\"noopener\"><strong>Castillo de San Marcos</strong></a>: Dopo pranzo, immergetevi nella storia visitando la pi&ugrave; antica fortezza in muratura degli Stati Uniti continentali. Costruita dagli spagnoli nel XVII secolo, &egrave; un luogo affascinante da esplorare, con i suoi bastioni, i cannoni e le viste sulla baia.</p>\r\n<p dir=\"ltr\" role=\"presentation\"><a href=\"https://www.flagler.edu/\" target=\"_blank\" rel=\"noopener\"><strong>Flagler College</strong></a>: Fate una passeggiata per ammirare l\'esterno di questo magnifico edificio. Un tempo era il lussuoso Hotel Ponce de Leon, costruito dal magnate Henry Flagler, e rappresenta uno degli esempi pi&ugrave; spettacolari di architettura neorinascimentale spagnola in America.</p>', '/usa/assets/images/giorno-12-pomeriggio.jpg'),
(29, 12, 4, 'Sera (17:30 in poi) ', 'Relax a St. Augustine Beach', '<p dir=\"ltr\" role=\"presentation\">Concludete la giornata con un po\' di meritato relax. Attraversate il Bridge of Lions e dirigetevi a <strong>St. Augustine Beach</strong>. Godetevi la vasta spiaggia di sabbia bianca, fate un bagno nell\'oceano e ammirate il tramonto dal molo.</p>\r\n<p dir=\"ltr\" role=\"presentation\">Cenate in uno dei tanti ristoranti sulla spiaggia o tornate nel quartiere storico per la cena.</p>', '/usa/assets/images/giorno-12-sera.jpg'),
(30, 13, 1, 'Mattina e Pomeriggio (08:30 - 17:00)', 'Kennedy Space Center', '<p dir=\"ltr\" role=\"presentation\"><strong>Partenza Anticipata</strong>: Dopo una colazione veloce, effettuate il check-out dall\'hotel di St. Augustine. La giornata di oggi &egrave; dedicata a un\'icona della storia americana: lo spazio.</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Dettagli del Percorso</strong>:<br><strong>Tragitto</strong>: St. Augustine, FL &rarr; Kennedy Space Center Visitor Complex, FL<br><strong>Strade Principali</strong>: I-95 South<br><strong>Distanza</strong>: circa 170 km<br><strong>Tempo stimato</strong>: 1 ora e 30 minuti</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Esplorazione del Kennedy Space Center<br></strong>Benvenuti al <a href=\"https://www.kennedyspacecenter.com/\" target=\"_blank\" rel=\"noopener\">Kennedy Space Center Visitor Complex</a>, il luogo da cui l\'umanit&agrave; &egrave; partita alla conquista della Luna. Non &egrave; un parco a tema, ma un vero e proprio centro spaziale operativo con un\'area visitatori incredibilmente ben fatta. Dedicate almeno 5-6 ore alla visita.</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Cosa non perdere</strong>:<br><strong>Space Shuttle Atlantis</strong>: Entrate nell\'edificio per trovarvi faccia a faccia con il vero Space Shuttle Atlantis, esposto come se fosse in volo. La presentazione multimediale iniziale &egrave; da brividi.<br><strong>Shuttle Launch Experience</strong>: Un simulatore che vi far&agrave; provare le sensazioni di un lancio verticale dello Shuttle. Molto divertente per le ragazze.<br><strong>Bus Tour e Apollo/Saturn V Center</strong>: Questo &egrave; un must. Il tour in bus vi porter&agrave; all\'interno delle aree operative della NASA, passando accanto al Vehicle Assembly Building (VAB) e alle rampe di lancio. Il tour termina all\'impressionante Apollo/Saturn V Center, dove potrete camminare sotto un gigantesco razzo Saturn V, lo stesso tipo che port&ograve; gli astronauti sulla Luna.<br><strong>Gateway</strong>: The Deep Space Launch Complex: Un\'area pi&ugrave; recente dedicata al futuro dell\'esplorazione spaziale, con navicelle e prototipi di compagnie come SpaceX.</p>', '/usa/assets/images/giorno-13-pomeriggio.jpg'),
(31, 13, 2, 'Tardo Pomeriggio (17:00 in poi)', 'Viaggio finale verso Orlando', '<p dir=\"ltr\" role=\"presentation\"><strong>Dettagli del Percorso</strong>:<br><strong>Tragitto</strong>: Kennedy Space Center &rarr; Zona Aeroporto di Orlando (MCO)<br><strong>Strade Principali</strong>: FL-528 W (Beachline Expressway - strada a pedaggio)<br><strong>Distanza</strong>: circa 75 km<br><strong>Tempo stimato</strong>: 50-60 minuti</p>\r\n<p><strong>Sera</strong>: Arrivo a Orlando, check-in in un hotel in zona aeroporto (MCO) per tutto il gruppo. Dopo una giornata cos&igrave; intensa, potete optare per una cena rilassante in hotel o in un ristorante vicino, oppure, se avete ancora energie, recarvi a Disney Springs per una cena pi&ugrave; vivace.</p>', '/usa/assets/images/giorno-13-sera.jpg'),
(32, 14, 1, 'Mattina', ' Divisione del Gruppo e Trasferimento', '<p>Mentre due adulti rientrano in Italia, il resto del gruppo si trasferisce in un hotel del <a href=\"https://www.universalorlando.com/web/en/us/places-to-stay/hotels/at-a-glance\" target=\"_blank\" rel=\"noopener\">Universal Orlando Resort</a>.</p>', '/usa/assets/images/giorno-14-mattina.jpg'),
(33, 14, 2, 'Pomeriggio e Sera', 'Relax e Divertimento', '<p>Pomeriggio di relax nelle fantastiche piscine del resort. Per cena, immergetevi nell\'atmosfera vibrante di <a href=\"https://www.universalorlando.com/web/en/us/things-to-do/dining/citywalk-dining\" target=\"_blank\" rel=\"noopener\">Universal CityWalk</a>.</p>', '/usa/assets/images/giorno-14-sera.jpg'),
(34, 15, 1, 'Mattina', 'Immersione nel Mondo Magico', '<p>Entrate a <strong>Universal Studios Florida</strong> e dirigetevi a <strong>Diagon Alley</strong>. L\'attrazione principale &egrave; <strong>Harry Potter and the Escape from Gringotts</strong>.</p>', '/usa/assets/images/giorno-15-mattina.jpg'),
(35, 15, 2, 'Pomeriggio', 'Avventure Cinematografiche', '<p>Dopo un pranzo al <strong>Leaky Cauldron</strong>, esplorate il resto del parco, da Springfield dei Simpsons a MEN IN BLACK Alien Attack.</p>', '/usa/assets/images/giorno-15-pomeriggio.jpg'),
(36, 16, 1, 'Mattina', 'Avventure Magiche a Hogsmeade', '<p>Entrate a <strong>Islands of Adventure</strong> e visitate <strong>Hogsmeade</strong>. Non perdete <strong>Hagrid&rsquo;s Magical Creatures Motorbike Adventure</strong>.</p>', '/usa/assets/images/giorno-16-mattina.jpg'),
(37, 16, 2, 'Pomeriggio', 'Dinosauri e Supereroi', '<p>Dopo un pranzo ai <strong>Tre Manici di Scopa</strong>, affrontate il <strong>Jurassic World VelociCoaster</strong> e le attrazioni di <strong>Marvel Super Hero Island</strong>.</p>', '/usa/assets/images/giorno-16-pomeriggio.jpg'),
(38, 17, 1, 'Mattina', 'Esplorazione di Nuovi Mondi', '<p>Giornata dedicata alla scoperta del nuovissimo parco <strong>Epic Universe</strong>. La prima tappa &egrave; <strong>Super Nintendo World</strong> con l\'attrazione <strong>Mario Kart: Bowser\'s Challenge</strong>.</p>', '/usa/assets/images/giorno-17-mattina.jpg'),
(39, 17, 2, 'Pomeriggio', 'Draghi, Mostri e Magia', '<p>Dopo un pranzo al <strong>Toadstool Cafe</strong>, immergetevi nel mondo vichingo di <strong>How to Train Your Dragon &ndash; Isle of Berk</strong>, nel misterioso <strong>Dark Universe</strong> e nella nuova area magica del <strong>Ministry of Magic</strong>.</p>', '/usa/assets/images/giorno-17-pomeriggio.jpg'),
(40, 18, 1, 'Tutto il giorno', 'Opzioni per la Giornata', '<p>Una meritata pausa dai parchi. Potete scegliere tra un relax totale nella piscina del resort, una giornata di shopping a <a href=\"https://www.disneysprings.com/\" target=\"_blank\" rel=\"noopener\">Disney Springs</a>, o un\'avventura acquatica a <a href=\"https://www.universalorlando.com/web/en/us/theme-parks/volcano-bay\" target=\"_blank\" rel=\"noopener\">Volcano Bay</a>.</p>', '/usa/assets/images/giorno-18-piscina.jpg'),
(41, 19, 1, 'Mattina', 'Immersione nel Pianeta Batuu', '<p>Giornata ai <strong><a href=\"https://disneyworld.disney.go.com/destinations/hollywood-studios/\" target=\"_blank\" rel=\"noopener\">Disney\'s Hollywood Studios</a></strong>. L\'obiettivo &egrave; l\'area <strong>Star Wars: Galaxy\'s Edge</strong>. La prima missione &egrave; affrontare <strong>Star Wars: Rise of the Resistance</strong>.</p>', '/usa/assets/images/giorno-19-mattina.jpg'),
(42, 19, 2, 'Pomeriggio', 'Pilotare il Falcon e Oltre', '<p>Dopo un pranzo al <strong>Docking Bay 7</strong>, &egrave; il momento di pilotare il \"pezzo di ferraglia pi&ugrave; veloce della galassia\" su <strong>Millennium Falcon: Smugglers Run</strong>. Esplorate anche le altre aree del parco, come <strong>Toy Story Land</strong>.</p>', '/usa/assets/images/giorno-19-pomeriggio.jpg'),
(43, 20, 1, 'Tutto il giorno', 'Il Viaggio di Ritorno', '<p>Mattinata libera per preparare i bagagli, check-out e trasferimento all\'aeroporto di Orlando (MCO) per il volo di rientro. L\'arrivo a Milano Malpensa &egrave; previsto per il giorno successivo, pronti per tornare a Lugano con un bagaglio pieno di esperienze indimenticabili.</p>', '/usa/assets/images/giorno-20-pomeriggio.jpg'),
(46, 2, 2, 'Pranzo (13:00 - 14:00)', 'Un Classico Moderno da Shake Shack', '<p>Dopo la biciclettata a Central Park, vi troverete proprio a Columbus Circle, la location perfetta per un pranzo iconico e delizioso. Dimenticate i soliti fast food:&nbsp;Shake Shack&nbsp;è un\'istituzione newyorkese. Nato come un semplice carretto di hot dog a Madison Square Park, è diventato famoso in tutto il mondo per i suoi hamburger di alta qualità, le patatine fritte \"crinkle-cut\" e i suoi cremosi milkshake. L\'atmosfera è sempre vivace e informale. È la scelta ideale per un pasto che metterà d\'accordo tutto il gruppo.</p>', '/usa/assets/images/shakeshack.jpg'),
(47, 2, 5, 'Sera (19:00 in poi)', 'La cena', '<p>Dopo essere scesi dal Top of the Rock, avrete l\'imbarazzo della scelta per la cena. La zona del Rockefeller Center e di Midtown offre innumerevoli opzioni, dai ristoranti più eleganti a locali più casual.</p>', '/usa/assets/images/giorno-2.jpg'),
(48, 3, 4, 'Tardo Pomeriggio (17:30 - 19:00)', 'Chelsea Market', '<ul>\r\n<li dir=\"ltr\" aria-level=\"2\">\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Un Paradiso per il Palato</strong>: La passeggiata sulla High Line pu&ograve; concludersi scendendo direttamente al <a href=\"https://www.chelseamarket.com/\" target=\"_blank\" rel=\"noopener\"><strong>Chelsea Market</strong></a>. Questo mercato alimentare si trova nell\'ex fabbrica della Nabisco, dove, curiosit&agrave; per tutti, fu inventato il biscotto Oreo!</p>\r\n</li>\r\n<li dir=\"ltr\" aria-level=\"2\">\r\n<p dir=\"ltr\" role=\"presentation\"><strong>L\'Atmosfera</strong>: L\'interno mantiene il suo fascino industriale, con mattoni a vista e tubature. Oggi &egrave; un vivace corridoio pieno di stand gastronomici che offrono prelibatezze da tutto il mondo: tacos, aragoste fresche, sushi, noodles, dolci... &Egrave; il luogo perfetto per uno spuntino o per una cena informale e varia, dove ognuno pu&ograve; scegliere ci&ograve; che preferisce.</p>\r\n</li>\r\n</ul>', '/usa/assets/images/chelsea.jpg'),
(49, 3, 5, 'Sera (19:30 in poi)', 'Tramonto sull\'Hudson a Little Island', '<ul>\r\n<li dir=\"ltr\" aria-level=\"2\">\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Passeggiata verso il fiume</strong>: Usciti dal Chelsea Market, con una breve e piacevole passeggiata di 5-10 minuti verso ovest, raggiungerete l\'Hudson River Park.</p>\r\n</li>\r\n<li dir=\"ltr\" aria-level=\"2\">\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Little Island (Pier 55)</strong>: La vostra destinazione &egrave; <a href=\"https://littleisland.org/\" target=\"_blank\" rel=\"noopener\"><strong>Little Island</strong></a>, un incredibile parco pubblico che sembra galleggiare sull\'acqua, costruito su 132 colonne a forma di tulipano. &Egrave; un capolavoro di architettura e paesaggistica. Passeggiate sui suoi sentieri tortuosi, scoprite i suoi piccoli anfiteatri e raggiungete i punti panoramici.</p>\r\n</li>\r\n<li><strong>L\'esperienza serale</strong>: Arrivare qui verso le 19:30/20:00 in estate &egrave; perfetto. Potrete ammirare il tramonto sul New Jersey, con il sole che cala dietro il fiume Hudson, e poi vedere le luci di Lower Manhattan e della Statua della Libert&agrave; (in lontananza) che si accendono. L\'atmosfera &egrave; magica, sicura e molto rilassante, ideale per concludere una giornata intensa.</li>\r\n</ul>', '/usa/assets/images/giorno-3-sera.jpg'),
(50, 4, 2, 'Pranzo (13:00 - 14:00)', 'Soul Food', '<p>Per un\'immersione completa nella cultura locale, non perdete l\'occasione di gustare un pranzo a base di autentico \"Soul Food\", la ricca e saporita cucina tradizionale afroamericana originaria del sud degli Stati Uniti. Questo genere culinario è un vero e proprio viaggio attraverso la storia e le tradizioni.</p><p>Tra i ristoranti più celebri e storici in cui assaporare queste delizie,<a href=\"https://sylviasrestaurant.com/\"> <strong>Sylvia\'s Restaurant</strong></a>, si distingue come un\'istituzione. Fondato da Sylvia Woods, è un punto di riferimento per il Soul Food fin dagli anni \'60 e offre piatti iconici come il pollo fritto croccante, i maccheroni al formaggio, il collard greens e il pane di mais. Sebbene l\'atmosfera sia vibrante e l\'esperienza culinaria imperdibile, è bene tenere presente che il locale è spesso molto affollato, specialmente durante i weekend, quindi è consigliabile arrivare presto o considerare la possibilità di un\'attesa.</p>', '/usa/assets/images/sylvia.jpg'),
(51, 5, 2, 'Pranzo (13:00 - 14:00)', 'Pranzare in riva all\' Hudson', '<p>Pranzate in uno dei tanti locali all\'interno di Brookfield Place, un moderno centro commerciale con una food court di alta qualità e vista sul fiume Hudson.</p>', '/usa/assets/images/hudson.jpg'),
(53, 7, 2, 'Pranzo (12:30 - 13:30)', 'Asheville', '<p>Per il pranzo, immergetevi nell\'atmosfera vibrante del centro di Asheville. Avete diverse opzioni deliziose a vostra disposizione. Una scelta eccellente e molto apprezzata &egrave; <a href=\"https://www.farmburger.com/\" target=\"_blank\" rel=\"noopener\"><strong>Farm Burger</strong></a>, rinomato per i suoi hamburger preparati con ingredienti freschi e di provenienza locale, un\'ottima opportunit&agrave; per assaporare i sapori del Sud.</p>', '/usa/assets/images/burger.jpg'),
(54, 7, 4, 'Sera (18:30 in poi)', 'Rientro a Black Mountain', '<p dir=\"ltr\" role=\"presentation\">Dopo l\'escursione sulla Parkway, rientrate alla vostra base a Black Mountain, godendovi la tranquillit&agrave; delle montagne al tramonto.</p>\r\n<p dir=\"ltr\" role=\"presentation\"><strong>Cena</strong>: Per la seconda serata, provate un altro locale di Black Mountain. <strong>Black Mountain Kitchen &amp; Ale House</strong> offre ottimi piatti in un ambiente rilassato, con una vasta selezione di birre artigianali locali.</p>', '/usa/assets/images/kitchen.jpg'),
(55, 8, 3, 'Sera (19:30 in poi)', 'Cena con Vista', '<p dir=\"ltr\" role=\"presentation\">Per cena, dirigetevi verso <strong>Shem Creek</strong> a Mount Pleasant. Quest\'area &egrave; famosa per la sua passerella in legno lungo il torrente, da cui si possono spesso avvistare i delfini.</p>\r\n<p><strong>Consiglio Ristorante</strong>: <em>Vickery\'s Bar &amp; Grill</em> - Perfetto per una cena informale con una splendida vista sulle barche dei pescatori di gamberi e sulla palude. L\'atmosfera &egrave; rilassata e ideale per concludere la giornata.</p>', '/usa/assets/images/shem-creek.jpg'),
(56, 9, 2, 'Pranzo (13:00 - 14:00)', 'Pranzate in un locale a Mount Pleasant prima di partire.', '<p dir=\"ltr\" role=\"presentation\">Prima di lasciare Mount Pleasant, concedetevi un pranzo memorabile che vi immerga nei sapori autentici del Sud.<strong> <a href=\"https://www.pagesokragrill.com/\" target=\"_blank\" rel=\"noopener\">Page\'s Okra Grill</a></strong> &egrave; un\'opzione eccellente e altamente raccomandata per gustare la vera cucina locale. Qui potrete assaporare piatti classici come il gambero e grana, pollo fritto croccante, e naturalmente, l\'okra, un ortaggio simbolo della gastronomia meridionale. L\'atmosfera &egrave; accogliente e informale, perfetta per un pasto rilassante prima di proseguire il vostro viaggio.</p>', '/usa/assets/images/okra.jpg'),
(57, 10, 4, 'Sera (18:00 in poi)', 'Serata spettrale', '<p dir=\"ltr\" role=\"presentation\">Dopo una giornata cos&igrave; intensa e ricca di scoperte, la serata &egrave; libera per rilassarsi e godersi l\'atmosfera unica di Savannah. Per una cena leggera e informale, potreste esplorare i numerosi ristoranti lungo River Street, con le loro viste mozzafiato sul fiume e un\'ampia scelta di cucine, dal pesce fresco ai piatti tradizionali del Sud.</p>\r\n<p dir=\"ltr\" role=\"presentation\">Se siete amanti dei dolci, non potete perdervi un gelato da<strong> <a href=\"https://leopoldsicecream.com/\" target=\"_blank\" rel=\"noopener\">Leopold\'s Ice Cream</a></strong>, un\'autentica istituzione di Savannah che delizia i palati dal lontano 1919. Con la sua atmosfera d\'epoca e i sapori classici e innovativi, &egrave; il luogo ideale per concludere la giornata con una nota dolce.</p>\r\n<p dir=\"ltr\" role=\"presentation\">Per chi cerca un\'esperienza un po\' pi&ugrave; insolita e misteriosa, Savannah &egrave; famosa per i suoi tour serali dei fantasmi. La citt&agrave;, con la sua ricca storia e le sue antiche dimore, &egrave; considerata una delle pi&ugrave; infestate d\'America. Numerose compagnie offrono passeggiate guidate attraverso i quartieri storici, raccontando leggende e <strong>storie di fantasmi </strong>che popolano le strade e gli edifici pi&ugrave; antichi. Un\'esperienza suggestiva e divertente per scoprire il lato pi&ugrave; oscuro e affascinante di Savannah.</p>', '/usa/assets/images/giorno-10-sera.jpg'),
(58, 12, 2, 'Pranzo (12:30 - 14:00)', 'Vita da surfer', '<p>Per il pranzo, avrete l\'opportunit&agrave; di esplorare una variet&agrave; di locali situati comodamente vicino al parco o lungo la strada che conduce al centro storico. Se siete alla ricerca di un\'esperienza culinaria rilassata e in linea con lo spirito costiero, il<a href=\"https://www.saltlifefoodshack.com/\" target=\"_blank\" rel=\"noopener\"> <strong>Salt Life Food Shack</strong></a> &egrave; un\'opzione altamente raccomandata. Questo ristorante &egrave; rinomato per la sua atmosfera vivace e informale, che richiama lo stile di vita dei surfisti, e per la sua eccellente offerta di piatti a base di pesce fresco. Qui potrete gustare specialit&agrave; locali e frutti di mare preparati con cura, in un ambiente accogliente e informale, perfetto per una pausa rigenerante prima di continuare l\'esplorazione della zona.</p>', '/usa/assets/images/salt.jpg'),
(59, 5, 1, 'Mattina (9:30 - 13:00)', 'Memoria e Finanza', '<ul><li><strong>9/11 Memorial &amp; Museum:</strong> Iniziate la giornata con una visita toccante e necessaria al complesso del World Trade Center. Dedicate almeno 2-3 ore alla visita del museo, che ripercorre gli eventi dell\'11 settembre in modo profondo e rispettoso. All\'esterno, soffermatevi sulle due vasche commemorative, le \"Reflecting Pools\", costruite sulle impronte delle Torri Gemelle.<br>&nbsp;</li><li><strong>Oculus e Financial District:</strong> Ammirate l\'architettura futuristica dell\'Oculus, la stazione progettata da Santiago Calatrava, e fate una passeggiata nel Financial District per vedere la Borsa di Wall Street e la famosa statua del \"Charging Bull\".</li></ul>', '/usa/assets/images/memorial.jpg');
INSERT INTO `sezioni` (`id`, `giorno_id`, `ordine`, `sovratitolo`, `titolo`, `testo`, `immagine`) VALUES
(60, 5, 3, 'Pomeriggio (14:00 - 17:00)', 'Battery Park e Viaggio sull\'Acqua', '<p><strong>Battery Park:</strong> Passeggiate fino alla punta meridionale di Manhattan per godervi la vista sulla Statua della Libertà e su Ellis Island.</p><ul><li>&nbsp;</li><li><strong>NYC Ferry a DUMBO:</strong> Dirigetevi verso Pier 11 (Wall Street) e imbarcatevi sull\'<a href=\"https://www.ferry.nyc/\">NYC Ferry</a>. Il breve ma spettacolare viaggio in traghetto vi regalerà una prospettiva unica e indimenticabile dello skyline di Lower Manhattan, della Statua della Libertà e del Ponte di Brooklyn visto dall\'acqua, arrivando direttamente al molo di DUMBO.</li></ul>', '/usa/assets/images/ferry.jpg');

-- --------------------------------------------------------

--
-- Struttura della tabella `spese`
--

CREATE TABLE `spese` (
  `id` int(11) NOT NULL,
  `giorno_id` int(11) DEFAULT NULL,
  `descrizione` varchar(255) NOT NULL,
  `categoria` enum('Voli','Alloggi','Pasti','Attrazioni','Trasporti','Varie') NOT NULL,
  `luogo_id` int(11) DEFAULT NULL,
  `importo_stimato` decimal(10,2) DEFAULT 0.00,
  `importo_preventivo` decimal(10,2) DEFAULT 0.00,
  `importo_reale` decimal(10,2) DEFAULT 0.00,
  `valuta` varchar(3) NOT NULL DEFAULT 'CHF',
  `data_spesa` date DEFAULT NULL,
  `pagato_da` varchar(100) DEFAULT NULL,
  `diviso_per` text DEFAULT NULL,
  `metodo_pagamento` enum('Carta di Credito','Contanti','Bonifico','Altro') DEFAULT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `spese`
--

INSERT INTO `spese` (`id`, `giorno_id`, `descrizione`, `categoria`, `luogo_id`, `importo_stimato`, `importo_preventivo`, `importo_reale`, `valuta`, `data_spesa`, `pagato_da`, `diviso_per`, `metodo_pagamento`, `note`) VALUES
(43, NULL, 'Voli Intercontinentali A/R (MXP-JFK, MCO-MXP)', 'Voli', NULL, '9410.00', '6663.00', '0.00', 'CHF', NULL, 'Federico', 'Federico, Paola, Elanor, Francesca, Emilio, Lucrezia', NULL, 'Stima per 6 persone. con united'),
(44, NULL, 'Volo Interno (JFK-AVL)', 'Voli', NULL, '1448.00', '1448.00', '0.00', 'USD', NULL, 'Federico', 'Federico, Paola, Elanor, Francesca, Emilio, Lucrezia', NULL, 'Stima per 6 persone con bagaglio.'),
(45, NULL, 'Hotel a New York (5 notti)', 'Alloggi', 1, '5435.00', '2731.00', '0.00', 'USD', NULL, 'Federico', 'Federico, Paola, Elanor, Francesca, Emilio, Lucrezia', NULL, 'Aliz Hotel Times Square. Colazione compresa'),
(46, NULL, 'Hotel a Black Mountain (2 notti)', 'Alloggi', 2, '850.00', '715.00', '0.00', 'USD', NULL, 'Federico', 'Federico, Paola, Elanor, Francesca, Emilio, Lucrezia', NULL, 'Casa Airbnb'),
(47, NULL, 'Hotel a Mount Pleasant (1 notte)', 'Alloggi', 4, '480.00', '397.00', '0.00', 'USD', NULL, 'Federico', 'Federico, Paola, Elanor, Francesca, Emilio, Lucrezia', NULL, 'Stima per 3 camere. https://www.booking.com/Share-siOiDZ\r\n'),
(48, NULL, 'Hotel a Savannah (2 notti)', 'Alloggi', 5, '1080.00', '1041.00', '0.00', 'USD', NULL, 'Federico', 'Federico, Paola, Elanor, Francesca, Emilio, Lucrezia', NULL, 'Casa savannah su booking'),
(49, NULL, 'Hotel a St. Augustine (2 notti)', 'Alloggi', 7, '961.00', '802.00', '0.00', 'USD', NULL, 'Federico', 'Federico, Paola, Elanor, Francesca, Emilio, Lucrezia', NULL, 'Casa Airbnb. https://it.airbnb.ch/wishlists/viewonly/791090e6-9a0a-4ab8-9184-7dacfb690af6?s=67&unique_share_id=5c453a37-feec-4585-b4ca-bbd037a30542'),
(50, NULL, 'Hotel Aeroporto Orlando (1 notte)', 'Alloggi', 9, '468.00', '300.00', '0.00', 'CHF', NULL, 'Federico', 'Federico, Paola, Elanor, Francesca, Emilio, Lucrezia', NULL, 'Stima per 2 camere. Hyatt Place Orlando Airport\r\n\r\n'),
(51, NULL, 'Hotel Universal Orlando (6 notti)', 'Alloggi', 9, '2560.00', '1486.00', '0.00', 'USD', NULL, 'Federico', 'Federico, Paola, Elanor, Lucrezia', NULL, '1 camera per 4 al Cabana Bay'),
(52, NULL, 'Noleggio Auto (Minivan/SUV per 8 giorni)', 'Trasporti', 5, '1160.00', '1400.00', '0.00', 'USD', NULL, 'Federico', 'Federico, Paola, Elanor, Francesca, Emilio, Lucrezia', NULL, 'Da Asheville a Orlando. - Alamo'),
(53, NULL, 'Benzina (stima 1500 km)', 'Trasporti', 5, '180.00', '280.00', '0.00', 'USD', NULL, 'Federico', 'Federico, Paola, Elanor, Francesca, Emilio, Lucrezia', NULL, 'stima rifatta con chatgpt'),
(54, NULL, 'Parcheggi Vari (Sud)', 'Trasporti', 5, '184.00', '0.00', '0.00', 'USD', NULL, 'Federico', 'Federico, Paola, Elanor, Francesca, Emilio, Lucrezia', NULL, ''),
(55, NULL, 'Trasporti a New York (Metro e Taxi)', 'Trasporti', 1, '459.00', '424.00', '0.00', 'USD', NULL, 'Federico', 'Federico, Paola, Elanor, Francesca, Emilio, Lucrezia', NULL, 'Metro: 34$ - Trasferimento Aeroporti: 220$\r\n\r\n'),
(56, NULL, 'Budget Pasti - New York', 'Pasti', 1, '4000.00', '2400.00', '0.00', 'USD', NULL, 'Federico', 'Federico, Paola, Elanor, Francesca, Emilio, Lucrezia', NULL, 'Stima per 5 giorni x 6 persone. Pranzo e cena (media 80$)'),
(57, NULL, 'Budget Pasti - Tour del Sud', 'Pasti', 5, '5214.00', '3439.00', '0.00', 'USD', NULL, 'Federico', 'Federico, Paola, Elanor, Francesca, Emilio, Lucrezia', NULL, 'Stima per 8 giorni x 6 persone. Rivisto al ribasso per cene a casa'),
(58, NULL, 'Budget Pasti - Orlando', 'Pasti', 9, '4400.00', '3360.00', '0.00', 'USD', NULL, 'Federico', 'Federico, Paola, Elanor, Lucrezia', NULL, 'Stima per 7 giorni x 4 persone a circa 120USD'),
(59, NULL, 'Biglietti Attrazioni - New York', 'Attrazioni', 1, '692.00', '1044.00', '0.00', 'USD', NULL, 'Federico', 'Federico, Paola, Elanor, Francesca, Emilio, Lucrezia', NULL, 'Top of the rock 302\r\nTenement Museum 198\r\nCathedral of St John the Divine 82\r\nStudio museum Harlem 50\r\nConey Island 180\r\n9/11 memorial 232'),
(60, NULL, 'Biglietti Attrazioni - Tour del Sud', 'Attrazioni', 5, '996.00', '895.00', '0.00', 'USD', NULL, 'Federico', 'Federico, Paola, Elanor, Francesca, Emilio, Lucrezia', NULL, 'KSC: 500, Alligator: 230, Boone Hall 165\r\n\r\n'),
(61, NULL, 'Biglietti Parchi a Tema - Orlando', 'Attrazioni', 9, '2864.00', '4714.00', '0.00', 'USD', NULL, 'Federico', 'Federico, Paola, Elanor, Lucrezia', NULL, 'Include Universal con Express 3 days park to park e Disney 1 day per 4 persone.'),
(62, NULL, 'Visti ESTA per USA', 'Varie', NULL, '126.00', '126.00', '0.00', 'USD', NULL, 'Federico', 'Federico, Paola, Elanor, Francesca, Emilio, Lucrezia', NULL, 'Il costo dell\'autorizzazione ESTA per gli Stati Uniti è di $21 USD. Questo importo include una tassa di elaborazione di $4 USD e una tassa di autorizzazione di $17 USD, che viene pagata solo se la richiesta viene approvata.'),
(63, NULL, 'Assicurazione di Viaggio Completa', 'Varie', NULL, '1055.00', '510.00', '0.00', 'CHF', NULL, 'Federico', 'Federico, Paola, Elanor, Francesca, Emilio, Lucrezia', NULL, 'Stima per 6 persone per 3 settimane. Axa - tutti e 6');

-- --------------------------------------------------------

--
-- Struttura della tabella `tassi_cambio`
--

CREATE TABLE `tassi_cambio` (
  `id` int(11) NOT NULL,
  `valuta` varchar(3) NOT NULL,
  `tasso_a_chf` decimal(10,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tassi_cambio`
--

INSERT INTO `tassi_cambio` (`id`, `valuta`, `tasso_a_chf`) VALUES
(1, 'USD', '0.830000'),
(2, 'EUR', '0.961400');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti_autorizzati`
--

CREATE TABLE `utenti_autorizzati` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti_autorizzati`
--

INSERT INTO `utenti_autorizzati` (`id`, `email`) VALUES
(1, 'iseppi.federico@gmail.com');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `feedback_sezioni`
--
ALTER TABLE `feedback_sezioni`
  ADD PRIMARY KEY (`sezione_id`);

--
-- Indici per le tabelle `giorni`
--
ALTER TABLE `giorni`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parte_id` (`parte_id`);

--
-- Indici per le tabelle `iscritti`
--
ALTER TABLE `iscritti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indici per le tabelle `luoghi`
--
ALTER TABLE `luoghi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indici per le tabelle `pagamenti`
--
ALTER TABLE `pagamenti`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `parti`
--
ALTER TABLE `parti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indici per le tabelle `sezioni`
--
ALTER TABLE `sezioni`
  ADD PRIMARY KEY (`id`),
  ADD KEY `giorno_id` (`giorno_id`);

--
-- Indici per le tabelle `spese`
--
ALTER TABLE `spese`
  ADD PRIMARY KEY (`id`),
  ADD KEY `giorno_id` (`giorno_id`),
  ADD KEY `luogo_id` (`luogo_id`);

--
-- Indici per le tabelle `tassi_cambio`
--
ALTER TABLE `tassi_cambio`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `valuta` (`valuta`);

--
-- Indici per le tabelle `utenti_autorizzati`
--
ALTER TABLE `utenti_autorizzati`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `giorni`
--
ALTER TABLE `giorni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT per la tabella `iscritti`
--
ALTER TABLE `iscritti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `luoghi`
--
ALTER TABLE `luoghi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `pagamenti`
--
ALTER TABLE `pagamenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `parti`
--
ALTER TABLE `parti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `sezioni`
--
ALTER TABLE `sezioni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT per la tabella `spese`
--
ALTER TABLE `spese`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT per la tabella `tassi_cambio`
--
ALTER TABLE `tassi_cambio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `utenti_autorizzati`
--
ALTER TABLE `utenti_autorizzati`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `feedback_sezioni`
--
ALTER TABLE `feedback_sezioni`
  ADD CONSTRAINT `fk_feedback_sezione` FOREIGN KEY (`sezione_id`) REFERENCES `sezioni` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `giorni`
--
ALTER TABLE `giorni`
  ADD CONSTRAINT `fk_giorni_parti` FOREIGN KEY (`parte_id`) REFERENCES `parti` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `sezioni`
--
ALTER TABLE `sezioni`
  ADD CONSTRAINT `fk_sezioni_giorni` FOREIGN KEY (`giorno_id`) REFERENCES `giorni` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `spese`
--
ALTER TABLE `spese`
  ADD CONSTRAINT `fk_spese_giorni` FOREIGN KEY (`giorno_id`) REFERENCES `giorni` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_spese_luoghi` FOREIGN KEY (`luogo_id`) REFERENCES `luoghi` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
