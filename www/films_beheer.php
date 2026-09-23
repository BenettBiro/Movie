<?php
session_start();
require 'database.php';

if (!isset($_SESSION['id'])) {
    header('Location: inloggen.php');
    exit;
}

if (strtolower($_SESSION['rol'] ?? '') != 'medewerker') {
    header('Location: index.php');
    exit;
}

$films = $conn->query("SELECT * FROM Films ORDER BY Film_titel")->fetchAll(PDO::FETCH_ASSOC);

$statusBerichten = [
    'toegevoegd' => ['tekst' => 'Film toegevoegd.', 'type' => 'succes'],
    'bijgewerkt' => ['tekst' => 'Film bijgewerkt.', 'type' => 'succes'],
    'verwijderd' => ['tekst' => 'Film verwijderd.', 'type' => 'succes'],
    'fout_titel' => ['tekst' => 'Filmtitel is verplicht.', 'type' => 'fout'],
];
$melding = null;
if (isset($_GET['status']) && isset($statusBerichten[$_GET['status']])) {
    $melding = $statusBerichten[$_GET['status']];
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Films beheren</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="staff-bar">
        <div class="staff-bar-inner">
            <span class="staff-bar-label">Beheer</span>
            <a href="M_ingelogged.php" class="btn-grey">Dashboard</a>
            <a href="films_lijst.php" class="btn-grey active">Films beheren</a>
            <a href="M_users_beheer.php" class="btn-grey">Gebruikers beheren</a>
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
            <h1>Films beheren</h1>
            <p>Voeg films toe, bewerk ze of verwijder ze uit de database.</p>
            <a href="add_film.php" class="btn-blue" style="margin-top: 1rem; display: inline-block;">
                + Nieuwe film toevoegen
            </a>
        </div>
    </section>

    <main class="main-content">

        <?php if ($melding): ?>
            <p style="color: <?php echo $melding['type'] === 'succes' ? 'green' : 'var(--accent)'; ?>; font-weight: 600;">
                <?php echo htmlspecialchars($melding['tekst']); ?>
            </p>
        <?php endif; ?>

        <div class="detail-card">
            <div class="detail-content">
                <h2 class="detail-title">Alle films</h2>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Titel</th>
                            <th>Genre</th>
                            <th>Jaar</th>
                            <th>Taal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($films as $f): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($f['Film_titel']); ?></td>
                                <td><?php echo htmlspecialchars($f['Genre']); ?></td>
                                <td><?php echo htmlspecialchars($f['Jaar']); ?></td>
                                <td><?php echo htmlspecialchars($f['Taal']); ?></td>
                                <td>
                                    <a href="edit_film.php?id=<?php echo $f['Film_id']; ?>" class="btn-blue">
                                        Bewerken
                                    </a>
                                    <form method="POST" action="delete_film.php" style="display: inline;"
                                        onsubmit="return confirm('Weet je zeker dat je &quot;<?php echo htmlspecialchars(addslashes($f['Film_titel'])); ?>&quot; wilt verwijderen?');">
                                        <input type="hidden" name="film_id" value="<?php echo $f['Film_id']; ?>">
                                        <button type="submit" class="btn-red">Verwijderen</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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