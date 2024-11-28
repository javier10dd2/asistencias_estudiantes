<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de Profesor
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'Profesor') {
    header("Location: login.php");
    exit();
}

// Incluir el archivo de conexión desde la carpeta models
include_once "../models/conexion.php"; // Ruta corregida para incluir el archivo de conexión

// Obtener la lista de alumnos desde la base de datos, solo con nombre, curso, y estado
$sql = "SELECT id, nombre_alumno, curso, estado FROM alumnos_asistencias";
$result = $conn->query($sql);
$alumnos = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $alumnos[] = $row; // Guardar cada alumno en un arreglo
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alumnos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles_prof.css">
</head>
<body>

<?php
include_once "header.php"; // Ruta para incluir el archivo de cabecera
?>

<link rel="stylesheet" href="../css/lista_alumnos.css">

<!-- Barra de Navegación -->
<div class="navbar">
    <a href="../Views/profesor_dashboard.php"><i class="fas fa-home"></i> Inicio</a>
    <a href="../Controllers/agregar_alumno.php"><i class="fas fa-user-plus"></i> Agregar Alumnos</a>
    <a href="../Controllers/registrar_asistencia.php"><i class="fas fa-calendar-check"></i> Registrar Asistencias</a>
    <a href="../Views/informe_asistencia.php"><i class="fas fa-chart-line"></i> Informe de Asistencias</a>
    <a href="../Views/login.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
</div>

<div class="container table-container">
    <div class="header">Lista de Alumnos</div>

    <!-- Tabla de alumnos -->
    <div class="table-responsive mt-4">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Curso</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnos as $alumno): ?>
                    <tr>
                        <td><?php echo $alumno['nombre_alumno']; ?></td>
                        <td><?php echo $alumno['curso']; ?></td>

                        <!-- Botón de editar -->
                        <td>
                            <a href="editar_alumno.php?id=<?php echo $alumno['id']; ?>" class="btn btn-warning btn-sm">
                                Editar <i class="fa fa-edit"></i>
                            </a>
                        </td>

                        <!-- Botón de eliminar -->
                        <td>
                            <a href="eliminar_alumno.php?id=<?php echo $alumno['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar a este alumno?')">
                                Eliminar <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <a href="../controllers/agregar_alumno.php" class="btn btn-primary btn-lg" style="width: 250px;">
            Agregar Nuevo Alumno
        </a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
