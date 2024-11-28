<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de Profesor
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'Profesor') {
    header("Location: login.php");
    exit();
}

// Incluir la conexión a la base de datos
include_once "conexion.php"; // Asegúrate de que el archivo de conexión esté correcto

// Verificar si se recibieron los datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $curso = $_POST['curso'];

    // Validar que los campos no estén vacíos
    if (!empty($nombre) && !empty($curso)) {
        // Preparar la consulta para actualizar los datos del alumno
        $sql = "UPDATE alumnos SET nombre = '$nombre', curso = '$curso' WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            // Redirigir a la lista de alumnos después de guardar los cambios
            header("Location: lista_alumnos.php?success_edit=1");
            exit();
        } else {
            echo "Error al actualizar los datos: " . $conn->error;
        }
    } else {
        echo "Por favor, complete todos los campos.";
    }
}

// Cerrar la conexión
$conn->close();
?>
