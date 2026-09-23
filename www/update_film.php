<?php
session_start();
require 'database.php';

if (!isset($_SESSION['id']) || strtolower($_SESSION['rol'] ?? '') !== 'medewerker') {
    header('Location: index.php');
    exit;
}

$filmId = isset($_POST['film_id']) ? (int) $_POST['film_id'] : 0;
$titel = trim($_POST['Film_titel'] ?? '');
$subtitel = trim($_POST['Film_subtitel'] ?? '');
$jaar = trim($_POST['Jaar'] ?? '');
$genre = trim($_POST['Genre'] ?? '');
$duur = trim($_POST['Duur'] ?? '');
$taal = trim($_POST['Taal'] ?? '');
$land = trim($_POST['Land'] ?? '');
$url = trim($_POST['Film_url'] ?? '');

if ($filmId <= 0 || $titel === '') {
    header('Location: edit_film.php?id=' . $filmId . '&status=fout_titel');
    exit;
}

$sql = "UPDATE Films
        SET Film_titel = :titel, Film_subtitel = :subtitel, Jaar = :jaar,
            Genre = :genre, Duur = :duur, Taal = :taal, Land = :land, Film_url = :url
        WHERE Film_id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([
    'titel' => $titel,
    'subtitel' => $subtitel,
    'jaar' => $jaar !== '' ? (int) $jaar : null,
    'genre' => $genre,
    'duur' => $duur,
    'taal' => $taal,
    'land' => $land,
    'url' => $url,
    'id' => $filmId,
]);

header('Location: index.php');
exit;