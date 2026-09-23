<?php
session_start();
require 'database.php';

if (!isset($_SESSION['id']) || strtolower($_SESSION['rol'] ?? '') !== 'medewerker') {
    header('Location: index.php');
    exit;
}

$titel = trim($_POST['Film_titel'] ?? '');
$subtitel = trim($_POST['Film_subtitel'] ?? '');
$jaar = trim($_POST['Jaar'] ?? '');
$genre = trim($_POST['Genre'] ?? '');
$duur = trim($_POST['Duur'] ?? '');
$taal = trim($_POST['Taal'] ?? '');
$land = trim($_POST['Land'] ?? '');
$url = trim($_POST['Film_url'] ?? '');

if ($titel === '') {
    header('Location: add_film.php?status=fout_titel');
    exit;
}

$sql = "INSERT INTO Films (Film_titel, Film_subtitel, Jaar, Genre, Duur, Taal, Land, Film_url)
        VALUES (:titel, :subtitel, :jaar, :genre, :duur, :taal, :land, :url)";
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
]);

header('Location: films_beheer.php');
exit;