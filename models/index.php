<?php
session_start();

// Clase para verificar la autenticación del usuario
class VerificacionUsuario {
    public static function verificarAcceso($tipoRequerido) {
        if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== $tipoRequerido) {
            header("Location: login.php");
            exit();
        }
    }
}

// Clase para gestionar la base de datos de alumnos
class GestionAlumnos {
    public static function obtenerListaAlumnos($conn) {
        $sql = "SELECT id, nombre_alumno, curso, fecha, estado FROM alumnos_asistencias";
        return $conn->query($sql);
    }
    
    public static function agregarAlumno($conn, $nombre, $curso) {
        $sql = "INSERT INTO alumnos_asistencias (nombre_alumno, curso, fecha, estado) VALUES (?, ?, CURDATE(), 'ausente')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nombre, $curso);
        if ($stmt->execute()) {
            return $conn->insert_id;  // Devuelve el ID del nuevo alumno
        }
        return null;
    }
}

// Verificar si el usuario tiene el rol de Profesor
VerificacionUsuario::verificarAcceso('Profesor');

// Incluir archivos comunes
include_once "views/header.php";  // Ruta a la carpeta views
include_once "views/nav.php";     // Ruta a la carpeta views
include_once "../models/conexion.php"; // Ruta a la carpeta models (sube un nivel hacia la carpeta models)

// Si se envió el formulario de agregar alumno
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $curso = $_POST["curso"];
    
    // Agregar el alumno y obtener su ID
    $alumno_id = GestionAlumnos::agregarAlumno($conn, $nombre, $curso);
}

// Obtener lista de alumnos
$resultado = GestionAlumnos::obtenerListaAlumnos($conn);
?>

<div class="alumnos-row row">
    <div class="alumnos-title col-12">
        <h1 class="text-center">Alumnos</h1>
    </div>

    <!-- Formulario para agregar nuevo alumno -->
    <div class="alumnos-add-form col-12 mb-3">
        <form method="POST" action="">
            <div class="form-group">
                <label for="nombre">Nombre del Alumno:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="curso">Curso:</label>
                <input type="text" name="curso" id="curso" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-info">Agregar Alumno</button>
        </form>
    </div>

    <!-- Tabla de alumnos -->
    <div class="alumnos-table-container col-12">
        <div class="table-responsive">
            <table class="table table-bordered table-striped alumnos-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Curso</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado->num_rows > 0) {
                        while ($alumno = $resultado->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $alumno['id']; ?></td>
                                <td><?php echo $alumno['nombre_alumno']; ?></td>
                                <td><?php echo $alumno['curso']; ?></td>
                                <td><?php echo $alumno['fecha']; ?></td>
                                <td><?php echo $alumno['estado']; ?></td>
                                <td>
                                    <a class="btn btn-warning btn-sm alumnos-edit-button" href="editar_alumno.php?id=<?php echo $alumno['id']; ?>">
                                        Editar <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                                <td>
                                    <a class="btn btn-danger btn-sm alumnos-delete-button" href="eliminar_alumno.php?id=<?php echo $alumno['id']; ?>">
                                        Eliminar <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr><td colspan="7" class="text-center">No hay alumnos registrados</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>

