<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de Profesor
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'Profesor') {
    header("Location: login.php");
    exit();
}

include_once "../models/conexion.php"; // Asegúrate de incluir la ruta correcta

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar el alumno
    $sql = "DELETE FROM alumnos_asistencias WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: lista_alumnos.php");
    } else {
        echo "Error al eliminar el alumno.";
    }
}

$conn->close();
?>
