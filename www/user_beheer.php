<?php
session_start();
require 'database.php';

if (!isset($_SESSION['id']) || strtolower($_SESSION['rol'] ?? '') !== 'medewerker') {
    header('Location: index.php');
    exit;
}

$userId = isset($_POST['user_id']) ? (int) $_POST['user_id'] : 0;
$actie = $_POST['actie'] ?? '';
$zoekterm = $_POST['zoekterm'] ?? '';

// Voorkom dat een medewerker zichzelf per ongeluk degradeert/verwijdert via deze route
if ($userId <= 0 || $userId === (int) $_SESSION['id']) {
    header('Location: User_Search.php?zoekterm=' . urlencode($zoekterm));
    exit;
}

$status = '';

switch ($actie) {
    case 'maak_lid':
        $check = $conn->prepare("SELECT 1 FROM Members WHERE user_id = :id");
        $check->execute(['id' => $userId]);
        if (!$check->fetchColumn()) {
            $stmt = $conn->prepare("INSERT INTO Members (user_id, Join_date) VALUES (:id, CURDATE())");
            $stmt->execute(['id' => $userId]);
        }
        $status = 'lid_gemaakt';
        break;

    case 'verwijder_lid':
        $stmt = $conn->prepare("DELETE FROM Members WHERE user_id = :id");
        $stmt->execute(['id' => $userId]);
        $status = 'lid_ingetrokken';
        break;

    case 'maak_medewerker':
        $check = $conn->prepare("SELECT 1 FROM Employee WHERE user_id = :id");
        $check->execute(['id' => $userId]);
        if (!$check->fetchColumn()) {
            $stmt = $conn->prepare("INSERT INTO Employee (user_id, Start_date) VALUES (:id, CURDATE())");
            $stmt->execute(['id' => $userId]);
        }
        $status = 'medewerker_gemaakt';
        break;

    case 'verwijder_medewerker':
        $stmt = $conn->prepare("DELETE FROM Employee WHERE user_id = :id");
        $stmt->execute(['id' => $userId]);
        $status = 'medewerker_ingetrokken';
        break;

    case 'verwijder_gebruiker':
        $stmt = $conn->prepare("DELETE FROM Users WHERE user_id = :id");
        $stmt->execute(['id' => $userId]);
        $status = 'gebruiker_verwijderd';
        break;
}

header('Location: User_Search.php?zoekterm=' . urlencode($zoekterm) . ($status ? '&status=' . $status : ''));
exit;