<?php 
session_start();

// Verificar si el usuario está autenticado y tiene el rol de Profesor
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'Profesor') {
    header("Location: login.php");
    exit();
}

include_once "../models/conexion.php";

$start = date("Y-m-d");
$end = date("Y-m-d");

if (isset($_GET["start"])) {
    $start = $_GET["start"];
}
if (isset($_GET["end"])) {
    $end = $_GET["end"];
}

// Obtener el informe de asistencia
$sql = "SELECT nombre_alumno, 
                SUM(CASE WHEN estado = 'Asistió' THEN 1 ELSE 0 END) AS asistio,
                SUM(CASE WHEN estado = 'Tardanza' THEN 1 ELSE 0 END) AS tardanza,
                SUM(CASE WHEN estado = 'Faltó' THEN 1 ELSE 0 END) AS falto
          FROM alumnos_asistencias 
          WHERE fecha BETWEEN ? AND ? 
          GROUP BY nombre_alumno";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $start, $end);
$stmt->execute();
$result = $stmt->get_result();

$asistencias = [];
while ($row = $result->fetch_assoc()) {
    $asistencias[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Asistencia</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/informe_asistencias.css">
</head>

<body>

    <!-- Barra de Navegación -->
    <div class="navbar">
         <a href="../Views/profesor_dashboard.php"><i class="fas fa-home"></i> Inicio</a>
         <a href="../Controllers/agregar_alumno.php"><i class="fas fa-user-plus"></i> Agregar Alumnos</a>
         <a href="../Controllers/registrar_asistencia.php"><i class="fas fa-calendar-check"></i> Registrar Asistencias</a>
         <a href="../Views/informe_asistencia.php"><i class="fas fa-chart-line"></i> Informe de Asistencias</a>
         <a href="../Views/login.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>

    <div class="container mt-5">
    <h1 class="text-center mb-4" style="margin-top: 100px;">Informe de Asistencia</h1>

        <form action="informe_asistencia.php" method="get" class="form-inline justify-content-center mb-4">
            <div class="form-group mr-2">
                <label for="start">Fecha inicio: </label>
                <input type="date" class="form-control" name="start" value="<?php echo $start; ?>">
            </div>
            <div class="form-group mr-2">
                <label for="end">Fecha fin: </label>
                <input type="date" class="form-control" name="end" value="<?php echo $end; ?>">
            </div>
            <button type="submit" class="btn-generar">Generar Informe</button>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Asistió</th>
                    <th>Tardanza</th>
                    <th>Faltó</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($asistencias as $asistencia): ?>
                    <tr>
                        <td><?php echo $asistencia['nombre_alumno']; ?></td>
                        <td><?php echo $asistencia['asistio']; ?></td>
                        <td><?php echo $asistencia['tardanza']; ?></td>
                        <td><?php echo $asistencia['falto']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>
