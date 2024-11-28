<?php
// Función para obtener todos los usuarios
function getUsuarios() {
    $conexion = new mysqli('localhost', 'root', '', 'pag_asistencias');
    if ($conexion->connect_error) {
        echo json_encode(["mensaje" => "Error de conexión a la base de datos"]);
        return;
    }

    $query = "SELECT * FROM usuarios";
    $result = $conexion->query($query);

    if ($result->num_rows > 0) {
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
        echo json_encode($usuarios);
    } else {
        echo json_encode(["mensaje" => "No se encontraron usuarios"]);
    }

    $conexion->close();
}

// Función para crear un nuevo usuario
function createUsuario() {
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->nombre) || !isset($data->correo) || !isset($data->contrasena) || !isset($data->tipo)) {
        echo json_encode(["mensaje" => "Faltan campos requeridos"]);
        return;
    }

    $conexion = new mysqli('localhost', 'root', '', 'pag_asistencias');
    if ($conexion->connect_error) {
        echo json_encode(["mensaje" => "Error de conexión a la base de datos"]);
        return;
    }

    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, contrasena, tipo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $data->nombre, $data->correo, $data->contrasena, $data->tipo);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Usuario creado con éxito"]);
    } else {
        echo json_encode(["mensaje" => "Error al crear el usuario"]);
    }

    $stmt->close();
    $conexion->close();
}

// Función para actualizar un usuario (PUT)
function updateUsuario($id) {
    $data = json_decode(file_get_contents("php://input"));
    
    if (!isset($data->nombre) || !isset($data->correo) || !isset($data->contrasena) || !isset($data->tipo)) {
        echo json_encode(["mensaje" => "Faltan campos requeridos"]);
        return;
    }

    $conexion = new mysqli('localhost', 'root', '', 'pag_asistencias');
    if ($conexion->connect_error) {
        echo json_encode(["mensaje" => "Error de conexión a la base de datos"]);
        return;
    }

    $stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, correo = ?, contrasena = ?, tipo = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $data->nombre, $data->correo, $data->contrasena, $data->tipo, $id);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Usuario actualizado con éxito"]);
    } else {
        echo json_encode(["mensaje" => "Error al actualizar usuario"]);
    }

    $stmt->close();
    $conexion->close();
}

// Función para eliminar un usuario (DELETE)
function deleteUsuario($id) {
    $conexion = new mysqli('localhost', 'root', '', 'pag_asistencias');
    if ($conexion->connect_error) {
        echo json_encode(["mensaje" => "Error de conexión a la base de datos"]);
        return;
    }

    $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Usuario eliminado con éxito"]);
    } else {
        echo json_encode(["mensaje" => "Error al eliminar usuario"]);
    }

    $stmt->close();
    $conexion->close();
}
?>
