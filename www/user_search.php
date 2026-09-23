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

$statusBerichten = [
    'lid_gemaakt' => ['tekst' => 'Gebruiker is nu lid.', 'type' => 'succes'],
    'lid_ingetrokken' => ['tekst' => 'Lidmaatschap ingetrokken.', 'type' => 'succes'],
    'medewerker_gemaakt' => ['tekst' => 'Gebruiker is nu medewerker.', 'type' => 'succes'],
    'medewerker_ingetrokken' => ['tekst' => 'Medewerkersrol ingetrokken.', 'type' => 'succes'],
    'gebruiker_verwijderd' => ['tekst' => 'Gebruiker verwijderd.', 'type' => 'succes'],
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
    <title>Gebruikers zoeken</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="staff-bar">
        <div class="staff-bar-inner">
            <span class="staff-bar-label">Beheer</span>
            <a href="M_ingelogged.php" class="btn-grey">Dashboard</a>
            <a href="films_lijst.php" class="btn-grey">Films beheren</a>
            <a href="User_Search.php" class="btn-grey active">Gebruikers beheren</a>
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

                <?php if ($melding): ?>
                    <p
                        style="color: <?php echo $melding['type'] === 'succes' ? 'green' : 'var(--accent)'; ?>; font-weight: 600;">
                        <?php echo htmlspecialchars($melding['tekst']); ?>
                    </p>
                <?php endif; ?>

                <form method="POST" action="User_Search.php">
                    <input type="hidden" name="id" value="<?php echo $user['user_id']; ?>">
                    <input type="text" name="zoekterm" value="<?php echo htmlspecialchars($zoekterm); ?>"
                        placeholder="Zoek een lid/medewerker...">
                    <button type="submit" class="btn-blue">Zoek</button>
                </form>

                <?php if ($zoekterm !== ''): ?>
                    <h2 class="detail-title" style="margin-top: 2rem; font-size: 1.4rem;">
                        Resultaten voor "<?php echo htmlspecialchars($zoekterm); ?>"
                    </h2>

                    <?php if (empty($resultaten)): ?>
                        <p>Geen gebruikers gevonden.</p>
                    <?php else: ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Gebruikersnaam</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Acties</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resultaten as $r): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($r['Username']); ?></td>
                                        <td><?php echo htmlspecialchars($r['Email']); ?></td>
                                        <td>
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
                                        <td>
                                            <?php if ($r['user_id'] == $_SESSION['id']): ?>
                                                <span style="color:#9ca3af; font-size:0.85rem;">Dit ben jij</span>
                                            <?php else: ?>

                                                <?php if (empty($r['is_member'])): ?>
                                                    <form method="POST" action="manage_user.php" style="display:inline;">
                                                        <input type="hidden" name="user_id" value="<?php echo $r['user_id']; ?>">
                                                        <input type="hidden" name="actie" value="maak_lid">
                                                        <input type="hidden" name="zoekterm"
                                                            value="<?php echo htmlspecialchars($zoekterm); ?>">
                                                        <button type="submit" class="btn-blue">Maak lid</button>
                                                    </form>
                                                <?php else: ?>
                                                    <form method="POST" action="manage_user.php" style="display:inline;">
                                                        <input type="hidden" name="user_id" value="<?php echo $r['user_id']; ?>">
                                                        <input type="hidden" name="actie" value="verwijder_lid">
                                                        <input type="hidden" name="zoekterm"
                                                            value="<?php echo htmlspecialchars($zoekterm); ?>">
                                                        <button type="submit" class="btn-grey">Verwijder lidmaatschap</button>
                                                    </form>
                                                <?php endif; ?>

                                                <?php if (empty($r['is_employee'])): ?>
                                                    <form method="POST" action="manage_user.php" style="display:inline;">
                                                        <input type="hidden" name="user_id" value="<?php echo $r['user_id']; ?>">
                                                        <input type="hidden" name="actie" value="maak_medewerker">
                                                        <input type="hidden" name="zoekterm"
                                                            value="<?php echo htmlspecialchars($zoekterm); ?>">
                                                        <button type="submit" class="btn-blue">Maak medewerker</button>
                                                    </form>
                                                <?php else: ?>
                                                    <form method="POST" action="manage_user.php" style="display:inline;">
                                                        <input type="hidden" name="user_id" value="<?php echo $r['user_id']; ?>">
                                                        <input type="hidden" name="actie" value="verwijder_medewerker">
                                                        <input type="hidden" name="zoekterm"
                                                            value="<?php echo htmlspecialchars($zoekterm); ?>">
                                                        <button type="submit" class="btn-grey">Verwijder medewerker</button>
                                                    </form>
                                                <?php endif; ?>

                                                <form method="POST" action="manage_user.php" style="display:inline;"
                                                    onsubmit="return confirm('Weet je zeker dat je &quot;<?php echo htmlspecialchars(addslashes($r['Username'])); ?>&quot; volledig wilt verwijderen?');">
                                                    <input type="hidden" name="user_id" value="<?php echo $r['user_id']; ?>">
                                                    <input type="hidden" name="actie" value="verwijder_gebruiker">
                                                    <input type="hidden" name="zoekterm"
                                                        value="<?php echo htmlspecialchars($zoekterm); ?>">
                                                    <button type="submit" class="btn-red">Verwijder gebruiker</button>
                                                </form>

                                            <?php endif; ?>
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