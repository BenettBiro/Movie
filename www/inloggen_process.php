<?php
session_start();
require 'database.php';

if (!isset($_POST['submit']) || empty($_POST['Email']) || empty($_POST['Password'])) {
    header('Location: index.php');
    exit;
}

$emailForm = $_POST['Email'];
$passwordForm = $_POST['Password'];

$sql = "SELECT * FROM Users WHERE Email = :Email";
$stmt = $conn->prepare($sql);
$stmt->execute(['Email' => $emailForm]);
$dbuser = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dbuser) {
    $_GET['message'] = 'usernotfound';
    include 'login-message.php';
    exit;
}

if ($dbuser['Password'] !== $passwordForm) {
    $_GET['message'] = 'wrongpassword';
    include 'login-message.php';
    exit;
}



$_SESSION['id'] = $dbuser['user_id'];
$_SESSION['email'] = $dbuser['Email'];
$_SESSION['username'] = $dbuser['Username'];
$_SESSION['firstname']= $dbuser['firstname'];
$_SESSION['lastname']= $dbuser['lastname'];

if (strtolower($dbuser['rol']) === 'medewerker') {
    $_SESSION['rol'] = 'Medewerker';
    header('Location: M_ingelogged.php');
} elseif (strtolower($dbuser['rol']) === 'lid') {
    $_SESSION['rol'] = 'lid';
    header('Location: ingelogged.php?id=' . $_SESSION['id']);
} else {
    header('Location: index.php');
}
exit;