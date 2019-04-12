<?php 

$servername = "localhost";
$username = "root";
$password = "anuvipin";
$pdo = new PDO("mysql:host=$servername;dbname=eurolink", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>