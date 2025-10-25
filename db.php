<?php
$host = 'host.docker.internal'; // 👈 this connects to your PC's MySQL from Docker
$dbname = 'your_database_name';
$username = 'root'; // or your MySQL username
$password = '';     // or your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
