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

require 'database.php';

$id = $_SESSION['id'];
$sql = "SELECT * FROM Users WHERE user_id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Simpele statistieken voor het personeelsdashboard
$filmCount = $conn->query("SELECT COUNT(*) FROM Films")->fetchColumn();
$userCount = $conn->query("SELECT COUNT(*) FROM Users")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medewerker Dashboard</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="staff-bar">
        <div class="staff-bar-inner">

            <a href="films_beheer.php" class="btn-grey">Films beheren</a>
            <a href="user_beheer.php" class="btn-grey">Gebruikers beheren</a>
        </div>
    </div>

    <nav>
        <div class="nav-inner">
            <div class="nav-logo">
                Films
                <span class="staff-badge">Medewerker</span>
            </div>

            <div class="nav-right">
                <span class="timer-label">Welkom, <?php echo htmlspecialchars($user['Username']); ?></span>

                <a href="M_Dashboard.php?id=<?php echo $user['user_id']; ?>" class="btn-blue">
                    Mijn Gegevens
                </a>

                <a href="uitloggen.php?logout=1" class="btn-red">
                    Uitloggen
                </a>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-inner">
            <h1>Personeelsdashboard <span class="staff-badge">Medewerker</span></h1>
            <p>Overzicht van films en gebruikers. Gebruik de beheerlinks hierboven om wijzigingen door te voeren.</p>

            <div class="staff-stats">
                <div class="staff-stat-card">
                    <div class="num"><?php echo (int) $filmCount; ?></div>
                    <div class="label">Films in database</div>
                </div>
                <div class="staff-stat-card">
                    <div class="num"><?php echo (int) $userCount; ?></div>
                    <div class="label">Geregistreerde gebruikers</div>
                </div>
            </div>
        </div>
    </section>

    <main class="main-content">
        <h2>Films</h2>

        <div class="workouts-wrapper">

            <?php include 'films.php'; ?>

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