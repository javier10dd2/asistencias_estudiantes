<?php
// Función para crear la conexión a la base de datos
function conectarBaseDeDatos() {
    $host = 'localhost'; 
    $usuario = 'root'; 
    $contraseña = ''; 
    $base_de_datos = 'pag_asistencias'; 

    // Crear la conexión
    $conn = new mysqli($host, $usuario, $contraseña, $base_de_datos);

    // Verificar si la conexión fue exitosa
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    
    return $conn;
}

// Función para obtener las asistencias filtradas por nombre
function obtenerAsistencias($conn, $nombre = '') {
    // Consulta SQL para obtener los datos de asistencia, con filtro por nombre
    $sql = "SELECT a.id, a.nombre_alumno, a.curso, a.fecha, a.estado 
            FROM alumnos_asistencias a
            WHERE a.nombre_alumno LIKE ? 
            ORDER BY a.fecha DESC"; 

    $stmt = $conn->prepare($sql);
    $nombre_param = "%$nombre%"; // Se agrega el '%' para buscar coincidencias parciales
    $stmt->bind_param("s", $nombre_param);
    $stmt->execute();
    return $stmt->get_result();
}

// Conectar a la base de datos
$conn = conectarBaseDeDatos();

// Verificar si se ha enviado un nombre para la búsqueda
$nombre_buscar = isset($_POST['nombre']) ? $_POST['nombre'] : '';

// Obtener los datos de asistencia
$resultado = obtenerAsistencias($conn, $nombre_buscar);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Incluir Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/consultar_asistencia.css">
    <title>Consultar Asistencia</title>
</head>
<body>

    <!-- Barra de navegación -->
    <div class="navbar">
        <a href="../Views/alumno_dashboard.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="../Controllers/consultar_asistencia.php"><i class="fas fa-calendar-check"></i> Consultar Asistencias</a>
        <a href="../Views/login.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>

    <header>
        <h1>Consulta de Asistencia de Estudiantes</h1>
    </header>

    <main>
        <!-- Formulario de búsqueda -->
        <form method="POST" action="consultar_asistencia.php">
            <label for="nombre">Buscar por nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre_buscar) ?>" placeholder="Escribe el nombre del alumno">
            <button type="submit">Buscar</button>
        </form>

        <?php if ($resultado->num_rows > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Alumno</th>
                    <th>Curso</th>
                    <th>Fecha de Asistencia</th>
                    <th>Estado de Asistencia</th>
                </tr>
                <?php while($fila = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= $fila['id'] ?></td>
                        <td><?= $fila['nombre_alumno'] ?></td>
                        <td><?= $fila['curso'] ?></td>
                        <td><?= $fila['fecha'] ?></td>
                        <td><?= $fila['estado'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p class="no-registros">No se encontraron registros de asistencia.</p>
        <?php endif; ?>
    </main>

    <?php
    // Cerrar la conexión a la base de datos
    $conn->close();
    ?>

</body>
</html>

