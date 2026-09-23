<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: inloggen.php');
    exit;
}

if (strtolower($_SESSION['rol'] ?? '') != 'medewerker') {
    header('Location: index.php');
    exit;
}

$statusBerichten = [
    'fout_titel' => 'Filmtitel is verplicht.',
];
$fout = null;
if (isset($_GET['status']) && isset($statusBerichten[$_GET['status']])) {
    $fout = $statusBerichten[$_GET['status']];
}

$genres = ['Actie', 'Avontuur', 'Animatie', 'Komedie', 'Drama', 'Fantasy', 'Horror', 'Misdaad', 'Romantiek', 'Sciencefiction', 'Thriller', 'Documentaire'];
$talen = ['Nederlands', 'Engels', 'Frans', 'Duits', 'Spaans', 'Italiaans', 'Koreaans', 'Japans'];
$landen = ['Nederland', 'België', 'Verenigde Staten', 'Verenigd Koninkrijk', 'Frankrijk', 'Duitsland', 'Spanje', 'Italië', 'Zuid-Korea', 'Japan'];
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe film</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="staff-bar">
        <div class="staff-bar-inner">
            <span class="staff-bar-label">Beheer</span>
            <a href="M_ingelogged.php" class="btn-grey">Dashboard</a>
            <a href="films_lijst.php" class="btn-grey active">Films beheren</a>
            <a href="User_Search.php" class="btn-grey">Gebruikers beheren</a>
        </div>
    </div>

    <nav>
        <div class="nav-inner">
            <div class="nav-logo">
                Films
                <span class="staff-badge">Medewerker</span>
            </div>
            <div class="nav-right">
                <a href="M_ingelogged.php" class="btn-blue">Hoofdpagina</a>
                <a href="uitloggen.php?logout=1" class="btn-red">Uitloggen</a>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-inner">
            <h1>Nieuwe film toevoegen</h1>
        </div>
    </section>

    <main class="main-content">
        <div class="detail-card">
            <div class="detail-content">

                <?php if ($fout): ?>
                    <p style="color: var(--accent); font-weight: 600;"><?php echo htmlspecialchars($fout); ?></p>
                <?php endif; ?>

                <form method="POST" action="film_make.php">
                    <div class="form-group">
                        <label>Titel</label>
                        <input type="text" name="Film_titel" required>
                    </div>

                    <div class="form-group">
                        <label>Subtitel</label>
                        <input type="text" name="Film_subtitel">
                    </div>

                    <div class="form-group">
                        <label>Jaar</label>
                        <input type="number" name="Jaar">
                    </div>

                    <div class="form-group">
                        <label>Genre</label>
                        <select name="Genre">
                            <option value="">-- Kies een genre --</option>
                            <?php foreach ($genres as $g): ?>
                                <option value="<?php echo htmlspecialchars($g); ?>"><?php echo htmlspecialchars($g); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Duur</label>
                        <input type="text" name="Duur">
                    </div>

                    <div class="form-group">
                        <label>Taal</label>
                        <select name="Taal">
                            <option value="">-- Kies een taal --</option>
                            <?php foreach ($talen as $t): ?>
                                <option value="<?php echo htmlspecialchars($t); ?>"><?php echo htmlspecialchars($t); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Land</label>
                        <select name="Land">
                            <option value="">-- Kies een land --</option>
                            <?php foreach ($landen as $l): ?>
                             <option value="<?php echo htmlspecialchars($l); ?>"><?php echo htmlspecialchars($l); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Afbeelding (bestandsnaam)</label>
                        <input type="text" name="Film_url">
                    </div>

                    <button type="submit" class="login-btn">Toevoegen</button>

                    <a href="films_lijst.php" class="btn-grey" style="display: inline-block; margin-top: 0.75rem;">
                        Annuleren
                    </a>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-inner">
            <div>
                <p>Ontdek onze grote selectie films voor iedereen.
                    Wij helpen onze leden bij het vinden van de perfecte film.</p>
            </div>
            <div>
                <h4>Snelle Links</h4>
                <ul class="footer-links">
                    <li><a href="index.php">Films</a></li>
                </ul>
            </div>
            <div></div>
        </div>
    </footer>

</body>

</html>