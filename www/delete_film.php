<?php
session_start();
require 'database.php';

if (!isset($_SESSION['id']) || strtolower($_SESSION['rol'] ?? '') !== 'medewerker') {
    header('Location: index.php');
    exit;
}

$filmId = isset($_POST['film_id']) ? (int) $_POST['film_id'] : 0;

if ($filmId > 0) {
    $sql = "DELETE FROM Films WHERE Film_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $filmId]);
}

header('Location: films_beheer.php');
exit;