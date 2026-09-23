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

$id = $_SESSION['id'];
$sql = "SELECT * FROM Users WHERE user_id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$zoekterm = isset($_POST['zoekterm']) ? trim($_POST['zoekterm']) : '';
$resultaten = [];

if ($zoekterm !== '') {
    $sql = "SELECT u.*, m.Member_id AS is_member, e.Employee_id AS is_employee
            FROM Users u
            LEFT JOIN Members m  ON m.user_id = u.user_id
            LEFT JOIN Employee e ON e.user_id = u.user_id
            WHERE u.Username LIKE :zoekterm OR u.Email LIKE :zoekterm
            ORDER BY u.Username";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['zoekterm' => '%' . $zoekterm . '%']);
    $resultaten = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebruikers zoeken</title>
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
                    Welkom,
                    <?php echo htmlspecialchars($user['Username']); ?>
                </span>
                <a href="M_ingelogged.php" class="btn-blue">Hoofdpagina</a>
                <a href="uitloggen.php?logout=1" class="btn-red">Uitloggen</a>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-inner">
            <h1>Gebruikers zoeken</h1>
            <p>Zoek op gebruikersnaam of e-mailadres.</p>
        </div>
    </section>

    <main class="main-content">
        <div class="detail-card">
            <div class="detail-content">

                <form method="POST" action="User_Search.php">
                    <input type="hidden" name="id" value="<?php echo $user['user_id']; ?>">
                    <input type="text" name="zoekterm" value="<?php echo htmlspecialchars($zoekterm); ?>"
                        placeholder="Zoek een lid/medewerker...">
                    <button type="submit" class="btn-blue">Zoek</button>
                </form>

                <?php if ($zoekterm !== ''): ?>
                    <h2 class="detail-title" style="margin-top: 2rem; font-size: 1.4rem;">
                        Resultaten voor "
                        <?php echo htmlspecialchars($zoekterm); ?>"
                    </h2>

                    <?php if (empty($resultaten)): ?>
                        <p>Geen gebruikers gevonden.</p>
                    <?php else: ?>
                        <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                            <thead>
                                <tr style="text-align: left; border-bottom: 2px solid #e5e7eb;">
                                    <th style="padding: 0.6rem;">Gebruikersnaam</th>
                                    <th style="padding: 0.6rem;">Email</th>
                                    <th style="padding: 0.6rem;">Rol</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resultaten as $r): ?>
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 0.6rem;">
                                            <?php echo htmlspecialchars($r['Username']); ?>
                                        </td>
                                        <td style="padding: 0.6rem;">
                                            <?php echo htmlspecialchars($r['Email']); ?>
                                        </td>
                                        <td style="padding: 0.6rem;">
                                            <?php
                                            if (!empty($r['is_employee'])) {
                                                echo '<span class="staff-badge">Medewerker</span>';
                                            } elseif (!empty($r['is_member'])) {
                                                echo '<span class="staff-badge" style="background:#2563eb;">Lid</span>';
                                            } else {
                                                echo '<span style="color:#9ca3af;">Geen rol</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                <?php endif; ?>

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