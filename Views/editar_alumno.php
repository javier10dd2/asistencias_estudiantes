<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de Profesor
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'Profesor') {
    header("Location: login.php");
    exit();
}

include_once "../models/conexion.php"; // Asegúrate de que el archivo de conexión esté correcto

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos del alumno
    $sql = "SELECT id, nombre_alumno, curso FROM alumnos_asistencias WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $alumno = $result->fetch_assoc();
    } else {
        echo "Alumno no encontrado";
        exit();
    }
}

// Actualizar los datos del alumno
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $curso = $_POST['curso'];

    $sql = "UPDATE alumnos_asistencias SET nombre_alumno = ?, curso = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nombre, $curso, $id);

    if ($stmt->execute()) {
        header("Location: lista_alumnos.php");
    } else {
        echo "Error al actualizar el alumno.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Alumno</title>
    <link rel="stylesheet" href="css/styles_prof.css">
</head>
<body>

<?php
include_once "header.php";
include_once "nav.php";
?>

<div class="container">
    <div class="header">Editar Alumno</div>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="nombre">Nombre del Alumno</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $alumno['nombre_alumno']; ?>" required>
        </div>
        <div class="form-group">
            <label for="curso">Curso</label>
            <input type="text" id="curso" name="curso" class="form-control" value="<?php echo $alumno['curso']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Alumno</button>
    </form>
</div>

</body>
</html>
