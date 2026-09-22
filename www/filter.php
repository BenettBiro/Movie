<?php

$sql_genres = "SELECT DISTINCT Genre FROM Films WHERE Genre IS NOT NULL ORDER BY Genre ASC";
$sql_talen  = "SELECT DISTINCT Taal FROM Films WHERE Taal IS NOT NULL ORDER BY Taal ASC";
$sql_jaren  = "SELECT DISTINCT Jaar FROM Films WHERE Jaar IS NOT NULL ORDER BY Jaar DESC";

$genres = $conn->query($sql_genres)->fetchAll(PDO::FETCH_ASSOC);
$talen  = $conn->query($sql_talen)->fetchAll(PDO::FETCH_ASSOC);
$jaren  = $conn->query($sql_jaren)->fetchAll(PDO::FETCH_ASSOC);
?>

<select name="genre">
    <option value="">-- Alle genres --</option>
    <?php foreach ($genres as $genre): ?>
        <option value="<?php echo htmlspecialchars($genre['Genre']); ?>" <?php echo (($_GET['genre'] ?? '') == $genre['Genre']) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($genre['Genre']); ?>
        </option>
    <?php endforeach; ?>
</select>

<select name="taal">
    <option value="">-- Alle talen --</option>
    <?php foreach ($talen as $taal): ?>
        <option value="<?php echo htmlspecialchars($taal['Taal']); ?>" <?php echo (($_GET['taal'] ?? '') == $taal['Taal']) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($taal['Taal']); ?>
        </option>
    <?php endforeach; ?>
</select>

<select name="jaar">
    <option value="">-- Alle jaren --</option>
    <?php foreach ($jaren as $jaar): ?>
        <option value="<?php echo htmlspecialchars($jaar['Jaar']); ?>" <?php echo (($_GET['jaar'] ?? '') == $jaar['Jaar']) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($jaar['Jaar']); ?>
        </option>
    <?php endforeach; ?>
</select>