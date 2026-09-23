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

if (!isset($_GET['id']) || $_GET['id'] != $_SESSION['id']) {
    header('Location: M_dashboard.php?id=' . $_SESSION['id']);
    exit;
}

$id = $_SESSION['id'];
$sql = "SELECT * FROM Users WHERE user_id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$zoekterm = isset($_POST['zoekterm']) ? trim($_POST['zoekterm']) : '';

$totaal_films = $conn->query("SELECT COUNT(*) FROM Films")->fetchColumn();
$totaal_leden = $conn->query("SELECT COUNT(*) FROM Members")->fetchColumn();
$totaal_medewerkers = $conn->query("SELECT COUNT(*) FROM Employee")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="staff-bar">
        <div class="staff-bar-inner">
            <span class="staff-bar-label">Beheer</span>
            <a href="M_ingelogged.php" class="btn-grey">Dashboard</a>
            <a href="M_films_beheer.php" class="btn-grey">Films beheren</a>
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
                <span class="timer-label">
                    Welkom, <?php echo htmlspecialchars($user['Username']); ?>
                </span>
                <span class="timer-label">
                    Tijd op pagina: <span id="timer" class="timer">00:00</span>
                </span>
                <a href="M_ingelogged.php" class="btn-blue">
                    Hoofdpagina
                </a>
                <a href="uitloggen.php?logout=1" class="btn-red">Uitloggen</a>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-inner">
            <h1>Mijn Dashboard</h1>
            <p>Beheer hier de algemene gegevens.</p>

            <div class="staff-stats">
                <div class="staff-stat-card">
                    <div class="num"><?php echo (int) $totaal_films; ?></div>
                    <div class="label">Films in database</div>
                </div>
                <div class="staff-stat-card">
                    <div class="num"><?php echo (int) $totaal_leden; ?></div>
                    <div class="label">Leden</div>
                </div>
                <div class="staff-stat-card">
                    <div class="num"><?php echo (int) $totaal_medewerkers; ?></div>
                    <div class="label">Medewerkers</div>
                </div>
            </div>
        </div>
    </section>

    <main class="main-content">
        <div class="detail-card">
            <div class="detail-content">
                <h2 class="detail-title">Gebruikers zoeken</h2>
                <p class="detail-type">Zoek een lid of medewerker op naam.</p>

                <form method="POST" action="user_search.php">
                    <input type="hidden" name="id" value="<?php echo $user['user_id']; ?>">
                    <input type="text" name="zoekterm"
                        value="<?php echo htmlspecialchars($zoekterm); ?>"
                        placeholder="Zoek een lid/medewerker...">
                    <button type="submit" class="btn-blue">Zoek</button>
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

    <script>
        let seconden = 0;
        function updateTimer() {
            seconden++;
            const minuten = Math.floor(seconden / 60);
            const restSeconden = seconden % 60;
            document.getElementById('timer').textContent =
                String(minuten).padStart(2, '0') + ':' + String(restSeconden).padStart(2, '0');
        }
        setInterval(updateTimer, 1000);
    </script>

</body>

</html>