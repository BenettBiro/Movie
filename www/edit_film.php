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

if (!isset($_GET['id'])) {
    header('Location: films_lijst.php');
    exit;
}

$sql = "SELECT * FROM Films WHERE Film_id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => (int) $_GET['id']]);
$film = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$film) {
    header('Location: films_lijst.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film bewerken</title>
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
            <h1>Film bewerken</h1>
            <p><?php echo htmlspecialchars($film['Film_titel']); ?></p>
        </div>
    </section>

    <main class="main-content">
        <div class="detail-card">
            <div class="detail-content">

                <form method="POST" action="update_film.php">
                    <input type="hidden" name="film_id" value="<?php echo $film['Film_id']; ?>">

                    <div class="form-group">
                        <label>Titel</label>
                        <input type="text" name="Film_titel" required
                            value="<?php echo htmlspecialchars($film['Film_titel']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Subtitel</label>
                        <input type="text" name="Film_subtitel"
                            value="<?php echo htmlspecialchars($film['Film_subtitel']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Jaar</label>
                        <input type="number" name="Jaar"
                            value="<?php echo htmlspecialchars($film['Jaar']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Genre</label>
                        <input type="text" name="Genre"
                            value="<?php echo htmlspecialchars($film['Genre']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Duur</label>
                        <input type="text" name="Duur"
                            value="<?php echo htmlspecialchars($film['Duur']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Taal</label>
                        <input type="text" name="Taal"
                            value="<?php echo htmlspecialchars($film['Taal']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Land</label>
                        <input type="text" name="Land"
                            value="<?php echo htmlspecialchars($film['Land']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Afbeelding (bestandsnaam)</label>
                        <input type="text" name="Film_url"
                            value="<?php echo htmlspecialchars($film['Film_url']); ?>">
                    </div>

                    <button type="submit" class="login-btn">Opslaan</button>

                    <a href="films_lijst.php" class="btn-grey" style="display: inline-block; margin-top: 0.75rem;">
                        Annuleren
                    </a>
                </form>
            </div>
        </div>
    </main>

</body>

</html>