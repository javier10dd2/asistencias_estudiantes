<?php 
header("Content-Type: application/json");

// Incluir archivos de conexión y funciones
include_once "../models/conexion.php"; // Asegúrate de que la ruta sea correcta
require_once 'usuarios.php';
require_once 'asistencias.php';

// Obtener el método de la solicitud (GET, POST, PUT, DELETE)
$request_method = $_SERVER["REQUEST_METHOD"];
$request_uri = $_SERVER["REQUEST_URI"];

// Separar la URI en partes
$uri_segments = explode('/', trim($request_uri, '/'));

// Manejar las rutas de la API
if (count($uri_segments) > 2) {
    if ($uri_segments[2] === 'usuarios') {
        if ($request_method == 'GET') {
            getUsuarios();  // Obtener todos los usuarios
        } elseif ($request_method == 'POST') {
            createUsuario();  // Crear un nuevo usuario
        } elseif ($request_method == 'PUT') {
            updateUsuario($uri_segments[3]);  // Actualizar un usuario
        } elseif ($request_method == 'DELETE') {
            deleteUsuario($uri_segments[3]);  // Eliminar un usuario
        } else {
            echo json_encode(["mensaje" => "Método no permitido"]);
        }
    } elseif ($uri_segments[2] === 'asistencias') {
        if ($request_method == 'GET') {
            getAsistencias();  // Obtener todas las asistencias
        } elseif ($request_method == 'POST') {
            createAsistencia();  // Crear nueva asistencia
        } elseif ($request_method == 'PUT') {
            updateAsistencia($uri_segments[3]);  // Actualizar una asistencia
        } elseif ($request_method == 'DELETE') {
            deleteAsistencia($uri_segments[3]);  // Eliminar una asistencia
        } else {
            echo json_encode(["mensaje" => "Método no permitido"]);
        }
    } else {
        echo json_encode(["mensaje" => "Ruta no encontrada"]);
    }
} else {
    echo json_encode(["mensaje" => "Ruta no válida"]);
}
?>
