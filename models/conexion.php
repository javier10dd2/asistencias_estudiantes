<?php
$servername = "localhost";  // Cambia esto si tu servidor de base de datos es diferente
$username = "root";         // Tu usuario de base de datos
$password = "";             // Tu contraseña de base de datos
$dbname = "pag_asistencias"; // Nombre de la base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
