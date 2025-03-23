<?php
$host = 'localhost';
$user = 'root'; // Usuario de MySQL
$pass = '';     // Contraseña de MySQL
$db = 'escola';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
