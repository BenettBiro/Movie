<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: inloggen.php');
    exit;
}

if (strtolower($_SESSION['rol'] ?? '') != 'lid') {
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

$sql = "SELECT r.Reservering_id, r.Reserveerdatum, f.Film_id, f.Film_titel, f.Genre
        FROM Reserveringen r
        JOIN Films f ON f.Film_id = r.Film_id
        WHERE r.user_id = :id
        ORDER BY r.Reserveerdatum DESC";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $id]);
$reserveringen = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <div class="nav-logo">Films</div>
            <div class="nav-right">
                <span class="timer-label">
                    Welkom, <?php echo htmlspecialchars($user['Username']); ?>
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
                        <p><?php echo htmlspecialchars($user['Username']); ?></p>
                    </div>
                    <div class="detail-box">
                        <strong>Email</strong>
                        <p><?php echo htmlspecialchars($user['Email']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-card" style="margin-top: 2rem;">
            <div class="detail-content">
                <h2 class="detail-title">Mijn Reserveringen</h2>
                <p class="detail-type">Films die je hebt gereserveerd.</p>

                <?php if (empty($reserveringen)): ?>
                    <p>Je hebt nog geen films gereserveerd.</p>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Film</th>
                                <th>Genre</th>
                                <th>Gereserveerd op</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reserveringen as $r): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($r['Film_titel']); ?></td>
                                    <td><?php echo htmlspecialchars($r['Genre']); ?></td>
                                    <td><?php echo htmlspecialchars($r['Reserveerdatum']); ?></td>
                                    <td>
                                        <a href="detailpagina.php?name=<?php echo urlencode($r['Film_titel']); ?>"
                                            class="btn-blue">
                                            Bekijk
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-inner">
            <div>
                <h4>Over Ons</h4>
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