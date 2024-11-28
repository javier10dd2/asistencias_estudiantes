<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está autenticado y tiene el rol de Alumno
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'Alumno') {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "pag_asistencias";  

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Obtener las tareas asignadas a los alumnos
$queryTareas = "SELECT t.id, t.nombre, t.fecha_entrega, p.descripcion AS periodo, m.nombre AS materia
                FROM tareas t
                JOIN periodos p ON t.periodo_id = p.id
                JOIN materias m ON t.materia_id = m.id
                WHERE t.fecha_entrega >= CURDATE()";  // Solo tareas futuras
$tareasResult = mysqli_query($conn, $queryTareas);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles_alumno.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/ver_tareas.css">
    <title>Ver Tareas</title>
</head>
<body>

    <!-- Barra de Navegación -->
    <div class="navbar">
        <a href="../Views/alumno_dashboard.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="../Controllers/consultar_asistencia.php"><i class="fas fa-calendar-check"></i> Consultar Asistencias</a>
        <a href="../Views/login.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>

    <div class="header">
        Tareas Asignadas
    </div>

    <div class="container">
        <h2>Tareas Disponibles</h2>

        <!-- Tabla de tareas -->
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre de la Tarea</th>
                    <th>Fecha de Entrega</th>
                    <th>Periodo</th>
                    <th>Materia</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($tarea = mysqli_fetch_assoc($tareasResult)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($tarea['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($tarea['fecha_entrega']); ?></td>
                    <td><?php echo htmlspecialchars($tarea['periodo']); ?></td>
                    <td><?php echo htmlspecialchars($tarea['materia']); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>
</html>
