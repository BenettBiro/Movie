<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: inloggen.php');
    exit;
}

if ($_SESSION['rol'] != 'medewerker') {
    header('Location: index.php');
    exit;
}

require 'database.php';

$zoekterm = isset($_POST['zoekterm']) ? $_POST['zoekterm'] : '';
$users = [];

if (!empty($zoekterm)) {
    $sql = "SELECT * FROM Users WHERE firstname LIKE :zoekterm OR username LIKE :zoekterm OR Rol LIKE :zoekterm";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['zoekterm' => '%' . $zoekterm . '%']);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Zoekresultaten</title>
    <link rel="stylesheet" href="style.css">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <nav>
        <div class="nav-inner">
            <div class="nav-logo">Sports</div>
            <div class="nav-right">
                <a href="Employee_Dashboard.php?id=<?php echo $_SESSION['id']; ?>" class="btn-blue">Terug</a>
                <a href="uitloggen.php?logout=1" class="btn-red">Uitloggen</a>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-inner">
            <h1>Zoekresultaten</h1>
            <p>Resultaten voor: <strong><?php echo $zoekterm; ?></strong></p>
        </div>
    </section>

    <main class="main-content">
        <?php if (empty($user)): ?>
            <p>Geen gebruikers gevonden voor "<?php echo $zoekterm; ?>".</p>
        <?php else: ?>
            <div class="workouts-grid">
                <?php foreach ($users as $user): ?>
                    <div class="workout-card">
                        <div class="workout-card-body">
                            <strong><?php echo $user['firstname']; ?></strong>
                            <p>Gebruikersnaam: <?php echo $user['username']; ?></p>
                            <p>Email: <?php echo $user['email']; ?></p>
                            <p>Rol: <?php echo $user['Rol']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <div class="footer-inner">
            <div>
                <h4>Over Ons</h4>
                <p>Bibliotheek De Wijze Uil biedt een grote selectie boeken voor iedereen.</p>
            </div>
            <div></div>
            <div></div>
        </div>
    </footer>

</body>
</html>