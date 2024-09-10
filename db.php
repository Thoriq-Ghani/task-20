<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'task19';

try {
    $connection = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi ke database gagal: " . $e->getMessage());
}
?>
