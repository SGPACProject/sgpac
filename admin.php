<?php
session_start();
include 'db.php';

// Verificar si el usuario tiene permisos de administrador
if ($_SESSION['role'] !== 'administrador') {
    header("Location: index.php");
    exit();
}

// Procesar eliminación de usuario
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id = $id");
    echo "<script>alert('Usuario eliminado con éxito'); window.location='admin.php';</script>";
}

// Procesar modificación de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $id = intval($_POST['id']);
    $username = $conn->real_escape_string($_POST['username']);
    $role = $conn->real_escape_string($_POST['role']);
    $taller = $conn->real_escape_string($_POST['taller']);
    $turno = $conn->real_escape_string($_POST['turno']);
    $carrera = $conn->real_escape_string($_POST['carrera']);
    $semestre = $conn->real_escape_string($_POST['semestre']); // Corrección del nombre de la columna

    $query = "UPDATE users 
              SET username = '$username', 
                  role = '$role', 
                  Taller = '$taller', 
                  Turno = '$turno',
                  Carrera = '$carrera',
                  Semestre = '$semestre' 
              WHERE id = $id";

    if ($conn->query($query)) {
        echo "<script>alert('Usuario modificado con éxito'); window.location='admin.php';</script>";
    } else {
        echo "<script>alert('Error al modificar usuario: " . $conn->error . "');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #87CEFA, #4682B4);
            color: #fff;
            padding: 20px;
            margin: 0;
        }
      header {
            background-color: #1E90FF;
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between; /* Espaciado entre los elementos */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Contenedor de texto y menú */
        .header-left {
            display: flex;
            flex-direction: column;
        }

        /* Texto del encabezado */
        .header-text {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        /* Menú de navegación */
        nav {
            display: flex;
            gap: 15px;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 18px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        /* Logo alineado a la derecha */
        .logo {
            height: 90px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: rgba(0, 0, 0, 0.1);
        }
        table, th, td {
            border: 1px solid #fff;
        }
        th, td {
            text-align: center;
            padding: 10px;
        }
        th {
            background: #1E90FF;
        }
        a {
            color: #ff4d4d;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        form {
            display: inline;
        }
    </style>
</head>
<body>

<header>
        <div class="header-left">
            <span class="header-text">Sistema de Gestión de Puntos de Actividades Culturales</span>
            <nav>
                <a href="index.html">Login</a>
		<a href="admin_reiniciar.php">Reiniciar Puntos</a>
            </nav>
        </div>
        <img src="imgs/logo.png" alt="Logo del Proyecto" class="logo">
</header>

    <h1>Bienvenido, Administrador</h1>
    <p>Gestiona usuarios (crear, modificar y eliminar):</p>

    <!-- Formulario para agregar un usuario -->
    <h2>Agregar Usuario</h2>
    <form action="add_user.php" method="post">
        <input type="text" name="Taller" placeholder="Taller" required>
        <input type="text" name="id" placeholder="Matrícula" required>
        <input type="text" name="username" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>

        <select name="carrera" required>
	<option value="Global">Global</option>
	<option value="Informática">Informática</option>
	<option value="Derecho">Derecho</option>
	<option value="Nutrición">Nutrición</option>
	<option value="Alimentos">Alimentos</option>
	<option value="Agrícola">Agrícola</option>
        </select>

        <select name="semestre" required>
	<option value="Sin semestre">Sin semestre</option>
	<option value="1er semestre">1er semestre</option>
	<option value="2do semestre">2do semestre</option>
	<option value="3er semestre">3er semestre</option>
	<option value="4to semestre">4to semestre</option>
	<option value="5to semestre">5to semestre</option>
	<option value="6to semestre">6to semestre</option>
	<option value="7mo semestre">7mo semestre</option>
	<option value="8vo semestre">8vo semestre</option>
	<option value="9no semestre">9no semestre</option>
        </select>

       <select name="turno" required>
	<option value="Completo">Completo</option>
	<option value="Matutino">Matutino</option>
	<option value="Vespertino">Vespertino</option>
       </select>

        <select name="role" required>
            <option value="profesor">Profesor</option>
            <option value="alumno">Alumno</option>
            <option value="instructor">Instructor</option>
        </select>


        <button type="submit">Registrar</button>
    </form>

    <!-- Lista de usuarios -->
    <h2>Usuarios Registrados</h2>
    <table>
        <thead>
            <tr>
                <th>Taller</th>
                <th>Matrícula</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Turno</th>
                <th>Carrera</th>
	  <th>Semestre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM users");
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['Taller']}</td>
                        <td>{$row['id']}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['role']}</td>
                        <td>{$row['Turno']}</td>
                        <td>{$row['Carrera']}</td>
	         <td>{$row['Semestre']}</td>
                        <td>
                            <a href='?delete={$row['id']}' onclick='return confirm(\"¿Estás seguro de eliminar este usuario?\")'>Eliminar</a> |
                            <a href='#' onclick='showEditForm({$row['id']}, \"{$row['username']}\", \"{$row['role']}\", \"{$row['Taller']}\")'>Modificar</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Error al cargar usuarios: " . $conn->error . "</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Formulario para modificar usuario -->
    <div id="editForm" style="display:none;">
        <h2>Modificar Usuario</h2>
        <form method="post">
            <input type="hidden" name="id" id="editId">
            <input type="text" name="username" id="editUsername" placeholder="Usuario" required>
            <input type="text" name="taller" id="editTaller" placeholder="Taller" required>
            <select name="role" id="editRole" required>
                <option value="profesor">Profesor</option>
                <option value="alumno">Alumno</option>
                <option value="administrador">Administrador</option>
                <option value="instructor">Instructor</option> <!-- Agregar opción de instructor -->
            </select>

        <select name="carrera" required>
	<option value="Global">Global</option>
	<option value="Informática">Informática</option>
	<option value="Derecho">Derecho</option>
	<option value="Nutrición">Nutrición</option>
	<option value="Alimentos">Alimentos</option>
	<option value="Agrícola">Agrícola</option>
        </select>

        <select name="semestre" required>
	<option value="Sin semestre">Sin semestre</option>
	<option value="1er semestre">1er semestre</option>
	<option value="2do semestre">2do semestre</option>
	<option value="3er semestre">3er semestre</option>
	<option value="4to semestre">4to semestre</option>
	<option value="5to semestre">5to semestre</option>
	<option value="6to semestre">6to semestre</option>
	<option value="7mo semestre">7mo semestre</option>
	<option value="8vo semestre">8vo semestre</option>
	<option value="9no semestre">9no semestre</option>
        </select>

       <select name="turno" required>
	<option value="Completo">Completo</option>
	<option value="Matutino">Matutino</option>
	<option value="Vespertino">Vespertino</option>
       </select>

            <button type="submit" name="update_user">Guardar Cambios</button>
        </form>
    </div>

    <script>
        function showEditForm(id, username, role, taller) {
            document.getElementById('editForm').style.display = 'block';
            document.getElementById('editId').value = id;
            document.getElementById('editUsername').value = username;
            document.getElementById('editRole').value = role;
            document.getElementById('editTaller').value = taller;
            document.getElementById('editCarrera').value = carrera;
            document.getElementById('editSemestre').value = semestre;
        }
    </script>
</body>
</html>



