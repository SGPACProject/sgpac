<?php
session_start();
include 'db.php';

// Verificar que el usuario tenga rol de administrador
if ($_SESSION['role'] !== 'administrador') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Escapar los valores para evitar inyecciones SQL
    $Taller = $conn->real_escape_string($_POST['Taller']);
    $id = $conn->real_escape_string($_POST['id']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $role = $conn->real_escape_string($_POST['role']);

    // Consulta sin cifrar la contraseña, insertándola directamente
    $query = "INSERT INTO users (Taller, id, username, password, role) VALUES ('$Taller', '$id', '$username', '$password', '$role')";

    // Ejecutar la consulta
    if ($conn->query($query)) {
        echo "<script>alert('Usuario registrado con éxito'); window.location='admin.php';</script>";
    } else {
        echo "<script>alert('Error al registrar usuario'); window.location='admin.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario</title>
</head>
<body>
    <h1>Agregar Usuario</h1>
    <form action="" method="post">
        <input type="text" name="Taller" placeholder="Taller" required>
        <input type="text" name="id" placeholder="Matrícula" required>
        <input type="text" name="username" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <select name="role" required>
            <option value="profesor">Profesor</option>
            <option value="alumno">Alumno</option>
            <option value="instructor">Instructor</option> <!-- Nueva opción -->
        </select>
        <button type="submit">Registrar</button>
    </form>
</body>
</html>
