<?php
session_start();
include 'db.php';

// Habilitar la visualización de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $Id = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Consultar la base de datos para verificar las credenciales
    $query = "SELECT id, username, role FROM users WHERE (Id = '$Id' OR id = '$Id') AND password = '$password'";
    $result = $conn->query($query);

    // Verificar si el usuario existe y tiene credenciales válidas
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Guardar datos del usuario en la sesión
        $_SESSION['id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
		$_SESSION['username'] = $user['username']; 

        // Redirigir según el rol del usuario
        if ($user['role'] === 'administrador') {
            header('Location: admin.php');
            exit(); // Es importante usar exit() después de header()
        } elseif ($user['role'] === 'profesor') {
            header('Location: profesor.php');
            exit();
        } elseif ($user['role'] === 'alumno') {
            header('Location: alumno.php');
            exit();
        } elseif ($user['role'] === 'instructor') {
            header('Location: instructor.php');
            exit();
        }
    } else {
        echo "Usuario o contraseña incorrectos.";
    }
}
?>

<!-- Formulario de login -->
<form method="POST" action="login.php">
    <label for="username">ID:</label>
    <input type="text" name="username" required>
    <br>
    <label for="password">Contraseña:</label>
    <input type="password" name="password" required>
    <br>
    <input type="submit" value="Iniciar sesión">
</form>








