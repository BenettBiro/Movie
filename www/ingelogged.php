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

/*if (!isset($_GET['id']) || $_GET['id'] != $_SESSION['id']) {
    header('Location: Logged_in_MainPage.php?id=' . $_SESSION['id']);
    exit;
}
*/
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
    <title>Hoofdpagina</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <nav>
        <div class="nav-inner">
            <div class="nav-logo">Films</div>

            <div class="nav-right">
                <span class="timer-label">Welkom, <?php echo htmlspecialchars($user['Username']); ?></span>

                <a href="Personal_Dashboard.php?id=<?php echo $user['user_id']; ?>" class="btn-blue">
                    Mijn Dashboard
                </a>

                <a href="uitloggen.php?logout=1" class="btn-red">
                    Uitloggen
                </a>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-inner">
            <h1>Welkom, <?php echo htmlspecialchars($user['Username']); ?></h1>
            <p>Bekijk de onderstaande films die er momenteel zijn</p>
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