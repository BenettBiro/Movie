<?php
session_start();
require 'database.php';

if (!isset($_SESSION['id']) || strtolower($_SESSION['rol'] ?? '') !== 'lid') {
    header('Location: inloggen.php');
    exit;
}

$filmId = isset($_POST['film_id']) ? (int) $_POST['film_id'] : 0;
$filmNaam = isset($_POST['film_naam']) ? $_POST['film_naam'] : '';

if ($filmId <= 0) {
    header('Location: index.php');
    exit;
}

$checkSql = "SELECT 1 FROM Reserveringen WHERE user_id = :user_id AND Film_id = :film_id";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->execute([
    'user_id' => $_SESSION['id'],
    'film_id' => $filmId,
]);
$bestaatAl = (bool) $checkStmt->fetchColumn();

if ($bestaatAl) {
    $status = 'al_gereserveerd';
} else {
    $sql = "INSERT INTO Reserveringen (user_id, Film_id) VALUES (:user_id, :film_id)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'user_id' => $_SESSION['id'],
        'film_id' => $filmId,
    ]);
    $status = 'gereserveerd';
}

header('Location: detailpagina.php?name=' . urlencode($filmNaam) . '&status=' . $status);
exit;