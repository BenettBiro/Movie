<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Detailpagina</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php
    include 'database.php';

    $name = isset($_GET['name']) ? $_GET['name'] : '';

    if (empty($name)) {
        echo "<p>Geen boek geselecteerd.</p>";
        exit;
    }

    $sql = "SELECT * FROM Films WHERE Film_titel = :name";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['name' => $name]);

    $Film = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$Film) {
        echo "<p>Boek niet gevonden.</p>";
        exit;
    }
    ?>

    <a href="index.php" class="back-link">&larr; Back</a>
    
    
    

    <div class="detail-card">
        <img src="images/<?php echo htmlspecialchars($Film['Film_Url'] ? $Film['Film_Url'] : 'placeholder.jpg'); ?>"
            alt="<?php echo htmlspecialchars($Film['Film_Url']); ?>" class="detail-image">

        <div class="detail-content">
            <h1 class="detail-title"><?php echo htmlspecialchars($Film['Film_titel']); ?></h1>

            <p class="detail-type"><?php echo htmlspecialchars($Film['Genre']); ?></p>

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
        </div>
    </div>

</body>

</html>