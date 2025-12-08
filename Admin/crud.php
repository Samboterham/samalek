<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require 'conn.php';

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="assets/images/ualogo.png">
    <link rel="stylesheet" href="style.css">
    <title>Admin</title>
</head>
<body>
    <header class="admin-header">
        <div class="branding">
            <div class="img-container">
                <img src="assets/images/ualogo.png" alt="Utrecht's Archief">
            </div>
            <div class="branding-copy">
                <span>Samalek Admin</span>
                <strong>Catalogusbeheer</strong>
            </div>
        </div>
        <a href="logout.php" class="btn btn-bewerk">Log uit</a>
    </header>
    <main class="dashboard">
        <section class="dashboard-hero card">
            <h1>Beheer catalogusitems en hotspots stap voor stap</h1>
            <p class="lead">Gebruik dit overzicht om snel naar de juiste onderdelen te navigeren en volg de workflow zodat de panoramaviewer altijd up-to-date blijft.</p>
            <div class="cta-group">
                <a href="informatie.php" class="btn btn-bewerk">Open catalogus</a>
                <div class="session-note">
                    <span class="badge">Tip</span>
                    <p>Werk item voor item af en keer pas terug naar dit scherm als alle wijzigingen zijn opgeslagen.</p>
                </div>
            </div>
            <ol class="progress-steps">
                <li>
                    <span class="step-label">Stap 1</span>
                    <h3>Catalogus openen</h3>
                    <p>Klik op <strong>Open catalogus</strong> om de lijst in <code>informatie.php</code> te openen en selecteer het item dat je wil bijwerken.</p>
                </li>
                <li>
                    <span class="step-label">Stap 2</span>
                    <h3>Gegevens bewerken</h3>
                    <p>Gebruik <strong>Bewerk</strong> om beschrijving, catalogusnummer en hoofdafbeelding te actualiseren. Controleer velden op spelling voordat je opslaat.</p>
                </li>
                <li>
                    <span class="step-label">Stap 3</span>
                    <h3>Hotspots beheren</h3>
                    <p>Kies <strong>Hotspot toevoegen</strong> of bewerk bestaande punten om positie en tekst te verfijnen. Gebruik percentages zodat de positie overeenkomt met de panoramafoto.</p>
                </li>
                <li>
                    <span class="step-label">Stap 4</span>
                    <h3>Validatie</h3>
                    <p>Controleer het resultaat in de panoramaviewer en verifieer of alle hotspots correct openen voordat je naar het volgende item gaat.</p>
                </li>
            </ol>
        </section>
        <section class="guidance-grid">
            <article class="card guidance-card">
                <h3>Catalogusbeheer</h3>
                <ul>
                    <li>Werk catalogusnummers volgens het afgesproken patroon <strong>UA-XX-###</strong> bij.</li>
                    <li>Gebruik duidelijke, korte beschrijvingen van maximaal 200 tekens.</li>
                    <li>Controleer of nieuwe afbeeldingen via <strong>Bestand kiezen</strong> worden geüpload vóór het opslaan.</li>
                </ul>
            </article>
            <article class="card guidance-card">
                <h3>Hotspots</h3>
                <ul>
                    <li>Gebruik <strong>punt_positie_x</strong> en <strong>punt_positie_y</strong> waarden tussen 0 en 100.</li>
                    <li>Houd teksten kort en beschrijvend zodat modals overzichtelijk blijven.</li>
                    <li>Verwijder oude hotspots die niet meer zichtbaar moeten zijn.</li>
                </ul>
            </article>
            <article class="card guidance-card">
                <h3>Workflow</h3>
                <ul>
                    <li>Werk altijd van boven naar beneden in de tabel om geen items te overslaan.</li>
                    <li>Gebruik filters in je browser (Ctrl+F) om snel een catalogusnummer te vinden.</li>
                    <li>Noteer wijzigingen wanneer meerdere beheerders samenwerken.</li>
                </ul>
            </article>
            <article class="card guidance-card">
                <h3>Kwaliteit</h3>
                <ul>
                    <li>Controleer hotspots op mobiel door de panoramaviewer te testen.</li>
                    <li>Bevestig dat afbeeldingen helder zijn en geen compressie-artefacten tonen.</li>
                    <li>Gebruik consistente taal: Nederlands, formele stijl.</li>
                </ul>
            </article>
        </section>
        <section class="support-panel card">
            <div class="support-content">
                <div>
                    <h3>Veelgebruikte schermen</h3>
                    <ul class="link-list">
                        <li><a href="informatie.php">Catalogusoverzicht</a> – beheer hoofdinformatie.</li>
                        <li><a href="hotspot.php">Nieuwe hotspot</a> – voeg extra punten toe.</li>
                        <li><a href="login.php">Inlogscherm</a> – gebruik dit voor het testen van nieuwe accounts.</li>
                    </ul>
                </div>
                <div>
                    <h3>Controlelijst voordat je afrondt</h3>
                    <ul class="checklist">
                        <li>Alle velden opgeslagen zonder foutmeldingen.</li>
                        <li>Panoramaviewer toont de laatste wijzigingen.</li>
                        <li>Alle hotspots openen met correcte tekst en afbeelding.</li>
                    </ul>
                </div>
            </div>
            <p class="footer-note">Probleem gevonden? Noteer het ID van het item en deel het met het team zodat het snel kan worden opgelost.</p>
        </section>
    </main>
</body>
</html>