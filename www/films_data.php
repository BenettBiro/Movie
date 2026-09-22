<?php
include 'database.php';

$genre     = $_GET['genre'] ?? '';
$taal      = $_GET['taal'] ?? '';
$jaar      = $_GET['jaar'] ?? '';
$zoekterm  = $_GET['zoekterm'] ?? '';

$sql = "SELECT * FROM Films WHERE 1=1";
$params = [];

if (!empty($genre)) {
    $sql .= " AND Genre = :genre";
    $params['genre'] = $genre;
}

if (!empty($taal)) {
    $sql .= " AND Taal = :taal";
    $params['taal'] = $taal;
}

if (!empty($jaar)) {
    $sql .= " AND Jaar = :jaar";
    $params['jaar'] = $jaar;
}

if (!empty($zoekterm)) {
    $sql .= " AND (Film_titel LIKE :zoekterm1 OR Film_subtitel LIKE :zoekterm2)";
    $params['zoekterm1'] = "%$zoekterm%";
    $params['zoekterm2'] = "%$zoekterm%";
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$Film = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>