<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de Profesor
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'Profesor') {
    header("Location: login.php");
    exit();
}

// Incluir el archivo de conexión
$servidor = 'localhost';
$usuario = 'root';
$clave = '';
$baseDatos = 'pag_asistencias';
$conn = new mysqli($servidor, $usuario, $clave, $baseDatos);

// Verificar la conexión
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión: ' . $conn->connect_error]);
    exit();
}

// Verificar si los datos han sido enviados y son válidos
if (isset($_POST['nombre']) && isset($_POST['curso'])) {
    // Obtener los datos del formulario
    $nombre = $conn->real_escape_string(trim($_POST['nombre']));
    $curso = $conn->real_escape_string(trim($_POST['curso']));

    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($curso)) {
        echo json_encode(['status' => 'error', 'message' => 'Por favor, complete todos los campos.']);
        exit();
    }

    // Sentencia preparada para insertar el nuevo alumno
    $sql = $conn->prepare("INSERT INTO alumnos_asistencias (nombre_alumno, curso, fecha, estado) VALUES (?, ?, CURDATE(), 'ausente')");
    $sql->bind_param("ss", $nombre, $curso);

    // Ejecutar la consulta
    if ($sql->execute()) {
        // Retornar respuesta exitosa
        echo json_encode(['status' => 'success', 'message' => 'Alumno guardado exitosamente.']);
        exit();
    } else {
        // Mostrar el error si la consulta no fue exitosa
        echo json_encode(['status' => 'error', 'message' => 'Error al guardar el alumno: ' . $sql->error]);
        exit();
    }

    // Cerrar la sentencia
    $sql->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Datos no enviados correctamente.']);
}

// Cerrar la conexión
$conn->close();
?>
