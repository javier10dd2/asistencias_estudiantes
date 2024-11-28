<?php

function getAsistencias() {
    // Conectar a la base de datos
    $conexion = new mysqli('localhost', 'root', '', 'pag_asistencias');

    if ($conexion->connect_error) {
        echo json_encode(["mensaje" => "Error de conexión a la base de datos"]);
        return;
    }

    $query = "SELECT * FROM alumnos_asistencias";
    $resultado = $conexion->query($query);

    if ($resultado->num_rows > 0) {
        $asistencias = [];
        while ($row = $resultado->fetch_assoc()) {
            $asistencias[] = $row;
        }
        echo json_encode($asistencias);
    } else {
        echo json_encode(["mensaje" => "No hay asistencias registradas"]);
    }

    $conexion->close();
}

function createAsistencia() {
    // Obtener los datos de la asistencia desde la solicitud POST
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->nombre_alumno) || !isset($data->curso) || !isset($data->fecha) || !isset($data->estado)) {
        echo json_encode(["mensaje" => "Faltan campos requeridos"]);
        return;
    }

    // Conectar a la base de datos
    $conexion = new mysqli('localhost', 'root', '', 'pag_asistencias');

    if ($conexion->connect_error) {
        echo json_encode(["mensaje" => "Error de conexión a la base de datos"]);
        return;
    }

    // Inserción de la asistencia
    $stmt = $conexion->prepare("INSERT INTO alumnos_asistencias (nombre_alumno, curso, fecha, estado) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $data->nombre_alumno, $data->curso, $data->fecha, $data->estado);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Asistencia registrada con éxito"]);
    } else {
        echo json_encode(["mensaje" => "Error al registrar asistencia"]);
    }

    $stmt->close();
    $conexion->close();
}
?>
