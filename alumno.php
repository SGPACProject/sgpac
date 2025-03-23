<?php 
session_start();
include 'db.php';

// Verificar si el usuario tiene rol de alumno
if ($_SESSION['role'] !== 'alumno') {
    header("Location: index.php");
    exit();
}

$id = $_SESSION['id'];
$username = $_SESSION['username'];
$user_id = intval($id); // Definir $user_id correctamente

// Inicializar variables para mantener los valores del formulario
$punto1 = $punto2 = 0;
$materia1 = $materia2 = ""; 
$materia1_bloqueada = $materia2_bloqueada = false;

// Consultar los puntos existentes del instructor
$query = "SELECT * FROM grades WHERE user_id = $user_id";
$result = $conn->query($query);
    $result = $conn->query($query);

if ($result && $row = $result->fetch_assoc()) {
    $punto1 = $row['punto1'];
    $punto2 = $row['punto2'];
    $materia1 = $row['materia1'];
    $materia2 = $row['materia2'];

// Consultar el Taller del alumno
$query = "SELECT Taller FROM users WHERE id = $user_id";
$result = $conn->query($query);

if ($result && $row = $result->fetch_assoc()) {
    $taller = $row['Taller']; // Guardar el nombre del Taller
} else {
    $taller = "No asignado"; // En caso de que no tenga taller
}

// Consultar el turno del alumno
$query = "SELECT Turno FROM users WHERE id = $user_id";
$result = $conn->query($query);

if ($result && $row = $result->fetch_assoc()) {
    $turno = $row['Turno']; // Guardar el nombre del Turno
} else {
    $turno = "No asignado"; // En caso de que no tenga Turno
}


// Consultar la carrera del alumno
$query = "SELECT Carrera FROM users WHERE id = $user_id";
$result = $conn->query($query);

if ($result && $row = $result->fetch_assoc()) {
    $carrera = $row['Carrera']; // Guardar el nombre del Taller
} else {
    $carrera = "No asignado"; // En caso de que no tenga taller
}

    // Bloquear Materia 1 si ya está registrada
    $materia1_bloqueada = !empty($materia1) && $punto1 > 0;
    // Bloquear Materia 2 si ya está registrada
    $materia2_bloqueada = !empty($materia2) && $punto2 > 0;
}

// Procesar Materia 1
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['materia1_submit'])) {
    $materia1 = isset($_POST['materia1']) ? $_POST['materia1'] : "";
    $punto1 = isset($_POST['punto1']) ? floatval($_POST['punto1']) : 0;

    if ($punto1 <= 0 || $punto1 > 7) {
        echo "<script>alert('Los puntos de Materia 1 deben estar entre 1 y 7.'); window.location='alumno.php';</script>";
        exit();
    }

    if (!empty($materia1)) {
        $query = $result->num_rows > 0
            ? "UPDATE grades SET materia1 = '$materia1', punto1 = $punto1 WHERE user_id = $user_id"
            : "INSERT INTO grades (user_id, materia1, punto1) VALUES ($user_id, '$materia1', $punto1)";
        if ($conn->query($query) === TRUE) {
            echo "<script>alert('Materia 1 registrada correctamente.'); window.location='alumno.php';</script>";
        } else {
            echo "<script>alert('Error al registrar Materia 1: {$conn->error}');</script>";
        }
    }
}

// Procesar Materia 2
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['materia2_submit'])) {
    $materia2 = isset($_POST['materia2']) ? $_POST['materia2'] : "";
    $punto2 = isset($_POST['punto2']) ? floatval($_POST['punto2']) : 0;
    $puntos_restantes = max(0, 7 - $punto1);

    if ($punto2 > $puntos_restantes) {
        echo "<script>alert('No puedes asignar más de $puntos_restantes puntos en Materia 2.'); window.location='alumno.php';</script>";
        exit();
    }

    if (!empty($materia2)) {
        $query = $result->num_rows > 0
            ? "UPDATE grades SET materia2 = '$materia2', punto2 = $punto2 WHERE user_id = $user_id"
            : "INSERT INTO grades (user_id, materia2, punto2) VALUES ($user_id, '$materia2', $punto2)";
        if ($conn->query($query) === TRUE) {
            echo "<script>alert('Materia 2 registrada correctamente.'); window.location='alumno.php';</script>";
        } else {
            echo "<script>alert('Error al registrar Materia 2: {$conn->error}');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumno - Gestión de puntos</title>
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

    <h1>Bienvenido, <?php echo $username; ?> - Matrícula: <?php echo $id; ?> - Taller: <?php echo $taller; ?>
- Turno: <?php echo $turno; ?> - Carrera: <?php echo $carrera; ?> </h1>

<div style="margin-top: 20px; padding: 15px; background-color: rgba(0, 0, 0, 0.1); border-radius: 5px;">
    <h3 style="text-align: center;">Declaración y Reglamento</h3>
    <p style="text-align: justify;">
        <strong>Bajo protesta de decir verdad</strong>, aseguro que los datos aquí vertidos son ciertos y que he leído y 
        aceptado las condiciones del reglamento interno para alumnos de la Universidad Mexiquense del Bicentenario, así 
        como los lineamientos que emite el área de Cultura y Deporte.
    </p>

    <h3 style="text-align: center;">Puntos Otorgados</h3>
    <p style="text-align: justify;">
        Con respecto a la obtención de los puntos que el área de Cultura y Deporte otorgará a cada alumno por su 
        participación en las actividades, corresponde un total de <strong>7 puntos</strong> y estos serán repartidos 
        entre <strong>dos materias en total</strong>.
    </p>

    <p style="text-align: justify;">
        <strong>Los puntos son intransferibles y no son acumulables para parciales posteriores.</strong>
    </p>
</div>
   

 <p>Gestiona tus puntos:</p>

    <!-- Formulario Materia 1 -->
    <h3>Agregar Puntos para Materia 1</h3>
    <form method="post">
        <label>Materia 1:</label>
        <input type="text" name="materia1" value="<?php echo $materia1; ?>" 
               <?php echo $materia1_bloqueada ? 'readonly' : ''; ?> required>
        <label>Puntos:</label>
        <input type="number" name="punto1" min="1" max="7" value="<?php echo $punto1; ?>" 
               <?php echo $materia1_bloqueada ? 'readonly' : ''; ?> required>
        <br>
        <button type="submit" name="materia1_submit" <?php echo $materia1_bloqueada ? 'disabled' : ''; ?>>
            Guardar Materia 1
        </button>
    </form>

    <!-- Formulario Materia 2 -->
    <h3>Agregar Puntos para Materia 2</h3>
    <form method="post">
        <label>Materia 2:</label>
        <input type="text" name="materia2" value="<?php echo $materia2; ?>" 
               <?php echo $materia2_bloqueada ? 'readonly' : ''; ?> required>
        <label>Puntos:</label>
        <input type="number" name="punto2" min="0" max="<?php echo max(0, 7 - $punto1); ?>" 
               value="<?php echo $punto2; ?>" <?php echo $materia2_bloqueada ? 'readonly' : ''; ?> required>
        <br>
        <button type="submit" name="materia2_submit" <?php echo $materia2_bloqueada ? 'disabled' : ''; ?>>
            Guardar Materia 2
        </button>
    </form>

    <!-- Tabla de resultados -->
    <h2>Resumen de Puntos</h2>
    <table>
        <thead>
            <tr>
                <th>Materia</th>
                <th>Puntos</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($materia1)) : ?>
            <tr>
                <td><?php echo $materia1; ?></td>
                <td><?php echo $punto1; ?></td>
            </tr>
            <?php endif; ?>
            <?php if (!empty($materia2)) : ?>
            <tr>
                <td><?php echo $materia2; ?></td>
                <td><?php echo $punto2; ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
