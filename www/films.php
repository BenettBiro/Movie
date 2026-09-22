<?php
include 'films_data.php';
?>

<form method="GET">
    <input type="text" name="zoekterm" value="<?php echo isset($_GET['zoekterm']) ? $_GET['zoekterm'] : ''; ?>"
        placeholder="Zoek een Film...">
    <?php include 'filter.php'; ?>
    <button type="submit" class="btn-blue">Filter</button>
</form>

<div class="workouts-grid">
    <?php foreach ($Film as $Films): ?>
        <div class="workout-card">
            <img src="images/<?php echo $Films['Film_Url'] ? $Films['Film_Url'] : 'placeholder.jpg'; ?>"
                alt="<?php echo $Films['Film_titel']; ?>">

            <div class="workout-card-body">
                <div>
                    <h3><?php echo htmlspecialchars($Films['Film_titel']); ?></h3>
                    <p><?php echo htmlspecialchars($Films['Film_subtitel']); ?></p>
                    <p><?php echo htmlspecialchars($Films['Genre']); ?></p>
                    <h3> Taal:<?php echo htmlspecialchars($Films['Taal']); ?> </h3>
                    <h3><?php echo htmlspecialchars($Films['Duur']); ?> Minuten</h3>

                </div>
                <a href="detailpagina.php?name=<?php echo urlencode($Films['Film_titel']); ?>">Meer informatie →</a>
                
            </div>
        </div>
    <?php endforeach; ?>
</div>