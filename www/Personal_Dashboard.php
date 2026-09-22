<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: inloggen.php');
    exit;
}

if ($_SESSION['rol'] != 'lid') {
    header('Location: index.php');
    exit;
}

if (!isset($_GET['id']) || $_GET['id'] != $_SESSION['id']) {
    header('Location: Personal_Dashboard.php?id=' . $_SESSION['id']);
    exit;
}

require 'database.php';

$id = $_SESSION['id'];
$sql = "SELECT * FROM Users WHERE user_id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
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

    <nav>
        <div class="nav-inner">
            <div class="nav-logo">Sports</div>
            <div class="nav-right">
                <span class="timer-label">
                    Welkom, <?php echo $user['Username']; ?>
                </span>
                <span class="timer-label">
                    Tijd op pagina: <span id="timer" class="timer">00:00</span>
                </span>
                <a href="ingelogged.php?id=<?php echo $user['user_id']; ?>" class="btn-blue">
                    Hoofdpagina
                </a>
                <a href="uitloggen.php?logout=1" class="btn-red">Uitloggen</a>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-inner">
            <h1>Mijn Dashboard</h1>
            <p>Beheer hier je persoonlijke gegevens.</p>
        </div>
    </section>

    <main class="main-content">
        <div class="detail-card">
            <div class="detail-content">
                <h2 class="detail-title">Mijn Gegevens</h2>
                <p class="detail-type">Hier vind je een overzicht van jouw accountinformatie.</p>

                <div class="detail-info">
                    <div class="detail-box">
                        <strong>Gebruikersnaam</strong>
                        <p><?php echo $user['Username']; ?></p>
                    </div>
                    <div class="detail-box">
                        <strong>Voornaam</strong>
                        <p><?php echo $user['firstname']; ?></p>
                    </div>
                    <div class="detail-box">
                        <strong>Achternaam</strong>
                        <p><?php echo $user['lastname']; ?></p>
                    </div>

                    <div class="detail-box">
                        <strong>Email</strong>
                        <p><?php echo $user['Email']; ?></p>
                    </div>
                    <div class="detail-box">
                        <strong>Rol</strong>
                        <p><?php echo $user['rol']; ?></p>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-inner">
            <div>
                <h4>Over Ons</h4>
                <p>Wij zijn een sportclub die zich richt op het bevorderen van een gezonde levensstijl.</p>
            </div>
            <div>
                <h4>Snelle Links</h4>
                <ul class="footer-links">
                    <li><a href="#">Workouts</a></li>
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