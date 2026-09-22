<?php

$dbhost = 'mariadb';
$dbname = 'testdb';
$dbuser = 'root';
$dbpass = 'password';

$conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}




