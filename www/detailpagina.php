<?php
session_start();
require 'database.php';

$name = isset($_GET['name']) ? $_GET['name'] : '';

if (empty($name)) {
    echo "<p>Geen film geselecteerd.</p>";
    exit;
}

$sql = "SELECT * FROM Films WHERE Film_titel = :name";
$stmt = $conn->prepare($sql);
$stmt->execute(['name' => $name]);

$Film = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$Film) {
    echo "<p>Film niet gevonden.</p>";
    exit;
}

$isLid = isset($_SESSION['id']) && strtolower($_SESSION['rol'] ?? '') === 'lid';

$alGereserveerd = false;
if ($isLid) {
    $checkSql = "SELECT 1 FROM Reserveringen WHERE user_id = :user_id AND Film_id = :film_id";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->execute([
        'user_id' => $_SESSION['id'],
        'film_id' => $Film['Film_id'],
    ]);
    $alGereserveerd = (bool) $checkStmt->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Detailpagina</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <a href="ingelogged.php" class="back-link">&larr; Back</a>

    <div class="detail-card">
        <img src="images/<?php echo htmlspecialchars($Film['Film_Url'] ? $Film['Film_Url'] : 'placeholder.jpg'); ?>"
            alt="<?php echo htmlspecialchars($Film['Film_Url']); ?>" class="detail-image">

        <div class="detail-content">
            <h1 class="detail-title"><?php echo htmlspecialchars($Film['Film_titel']); ?></h1>

            <p class="detail-type"><?php echo htmlspecialchars($Film['Genre']); ?></p>

            <?php if (isset($_GET['status'])): ?>
                <?php if ($_GET['status'] === 'gereserveerd'): ?>
                    <p style="color: green; font-weight: 600;">Film gereserveerd!</p>
                <?php elseif ($_GET['status'] === 'al_gereserveerd'): ?>
                    <p style="color: #b45309; font-weight: 600;">Je had deze film al gereserveerd.</p>
                <?php endif; ?>
            <?php endif; ?>

            <div class="detail-info">

                <div class="detail-box">
                    <p><strong>Genre</strong></p>
                    <p><?php echo htmlspecialchars($Film['Genre']); ?></p>
                </div>

                <div class="detail-box">
                    <p><strong>Duur</strong></p>
                    <p><?php echo htmlspecialchars($Film['Duur']); ?></p>
                </div>

                <div class="detail-box">
                    <p><strong>Taal</strong></p>
                    <p><?php echo htmlspecialchars($Film['Taal']); ?></p>
                </div>

            </div>

            <?php if ($isLid): ?>
                <form method="POST" action="reserveren.php" style="margin-top: 1.5rem;">
                    <input type="hidden" name="film_id" value="<?php echo $Film['Film_id']; ?>">
                    <input type="hidden" name="film_naam" value="<?php echo htmlspecialchars($Film['Film_titel']); ?>">
                    <button type="submit" class="btn-blue" <?php echo $alGereserveerd ? 'disabled' : ''; ?>>
                        <?php echo $alGereserveerd ? 'Al gereserveerd' : 'Reserveer film'; ?>
                    </button>
                </form>
            <?php elseif (!isset($_SESSION['id'])): ?>
                <p style="margin-top: 1.5rem; color: var(--text-muted);">
                    <a href="inloggen.php">Log in</a> als lid om deze film te reserveren.
                </p>
            <?php endif; ?>

        </div>
    </div>

</body>

</html>