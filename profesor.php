<?php
session_start();
include 'db.php';

// Verificar si el usuario tiene rol de profesor
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'profesor') {
    header("Location: index.php");
    exit();
}

// Validar si el ID del usuario está definido
if (!isset($_SESSION['id'])) {
    die("Error: Sesión no iniciada correctamente. Por favor, vuelve a iniciar sesión.");
}

$user_id = intval($_SESSION['id']); // Convertir a entero para mayor seguridad

// Inicializar variables
$search_id = '';
$grades = [];

// Procesar búsqueda de matrícula
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $search_id = intval($_POST['search_id']);
    
    // Consultar los puntos del alumno con la matrícula buscada
    $query = "
    SELECT g.id, u.username, g.punto1, g.punto2, g.materia1, g.materia2, u.role, u.Taller 
    FROM grades g 
    JOIN users u ON g.user_id = u.id
    WHERE u.id = $search_id AND (u.role = 'alumno' OR u.role = 'instructor')";

    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $grades = $result->fetch_assoc(); // Obtener los datos del alumno
    } else {
        echo "<script>alert('No se encontró ningún alumno con esa matrícula.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesor - Puntos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #87CEFA, #4682B4);
            color: #fff;
            padding: 20px;
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
    </style>
</head>

<body>

    <header>
        <div class="header-left">
            <span class="header-text">Sistema de Gestión de Puntos de Actividades Culturales</span>
            <nav>
                <a href="index.php">Login</a>
            </nav>
        </div>
        <img src="imgs/logo.png" alt="Logo del Proyecto" class="logo">
    </header>

    <h1>Bienvenido, Profesor</h1>
    <p>Buscar matrícula de alumno:</p>

    <!-- Formulario de búsqueda -->
    <form method="post">
        <input type="text" name="search_id" placeholder="Ingrese matrícula" required>
        <button type="submit" name="search">Buscar</button>
    </form>

    <?php if (!empty($grades)): ?>
        <h2>Resultados de la Búsqueda</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Materia 1</th>
                    <th>Punto 1</th>
                    <th>Materia 2</th>
                    <th>Punto 2</th>
                    <th>Taller</th>
	      <th>Rol</th> <!-- Nueva columna para el rol -->
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $search_id; ?></td>
                    <td><?php echo $grades['username']; ?></td>
                    <td><?php echo $grades['materia1']; ?></td>
                    <td><?php echo $grades['punto1']; ?></td>
                    <td><?php echo $grades['materia2']; ?></td>
                    <td><?php echo $grades['punto2']; ?></td>
                    <td><?php echo $grades['Taller']; ?></td>
	     <td><?php echo $grades['role']; ?></td> <!-- Mostrar el rol -->
                 </tr>
        <?php else: ?>
            <tr>
                <td colspan="7">No se encontraron resultados.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>