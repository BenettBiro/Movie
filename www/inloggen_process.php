<?php
session_start();
require 'database.php';

if (!isset($_POST['submit']) || empty($_POST['Email']) || empty($_POST['Password'])) {
    header('Location: index.php');
    exit;
}

$emailForm = $_POST['Email'];
$passwordForm = $_POST['Password'];

$sql = "SELECT u.*, m.Member_id AS is_member, e.Employee_id AS is_employee
        FROM Users u
        LEFT JOIN Members m  ON m.user_id = u.user_id
        LEFT JOIN Employee e ON e.user_id = u.user_id
        WHERE u.Email = :Email";
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

session_regenerate_id(true);

$_SESSION['id'] = $dbuser['user_id'];
$_SESSION['email'] = $dbuser['Email'];
$_SESSION['username'] = $dbuser['Username'];

if (!empty($dbuser['is_employee'])) {
    $_SESSION['rol'] = 'medewerker';
    header('Location: M_ingelogged.php');
} elseif (!empty($dbuser['is_member'])) {
    $_SESSION['rol'] = 'lid';
    header('Location: ingelogged.php?id=' . $_SESSION['id']);
} else {
    header('Location: index.php');
}
exit;