<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de Profesor
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'Profesor') {
    header("Location: login.php");
    exit();
}

include_once "conexion.php"; // Asegúrate de que el archivo de conexión esté correcto

// Obtener los datos enviados del formulario
$date = $_POST['date'];
$alumnos = json_decode($_POST['alumnos'], true);

// Preparar la consulta para actualizar la asistencia de cada alumno
foreach ($alumnos as $alumno) {
    $estado = $alumno['estado']; // Obtener el estado de cada alumno
    $id = $alumno['id']; // Obtener el ID de cada alumno

    // Verificar si ya existe un registro de asistencia para este alumno en la fecha
    $sql_check = "SELECT id FROM alumnos_asistencias WHERE id = ? AND fecha = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("is", $id, $date);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Si ya existe, actualizamos el estado
        $sql_update = "UPDATE alumnos_asistencias SET estado = ? WHERE id = ? AND fecha = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sis", $estado, $id, $date);
        $stmt_update->execute();
    } else {
        // Si no existe, insertamos un nuevo registro de asistencia
        $sql_insert = "INSERT INTO alumnos_asistencias (id, nombre_alumno, curso, fecha, estado) 
                       VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("issss", $id, $alumno['nombre_alumno'], $alumno['curso'], $date, $estado);
        $stmt_insert->execute();
    }
}

$conn->close();

// Responder con un mensaje de éxito
echo json_encode(['message' => 'Asistencia guardada correctamente.']);
?>


