<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // Cambiar si tu MySQL tiene contraseña.
$db = 'escolar';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
} else {
    echo "Conexión exitosa a la base de datos.";
}
?>
