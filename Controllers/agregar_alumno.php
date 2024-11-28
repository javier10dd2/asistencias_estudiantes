<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de Profesor
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'Profesor') {
    header("Location: ../Views/login.php"); // Ruta actualizada
    exit();
}

// Incluir archivos comunes (header y estilos específicos)
include_once "../Views/header.php";

// Establecer la conexión a la base de datos directamente
$servidor = 'localhost';
$usuario = 'root';
$clave = '';
$baseDatos = 'pag_asistencias';
$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDatos);

// Verificar la conexión
if (!$enlace) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}
?>

<!-- Asegurarse que la ruta esté correcta -->
<link rel="stylesheet" href="../css/agregar_alumno.css">

<!-- Barra de Navegación -->
<div class="navbar">
<a href="../Views/profesor_dashboard.php"><i class="fas fa-home"></i> Inicio</a>
    <a href="../Controllers/agregar_alumno.php"><i class="fas fa-user-plus"></i> Agregar Alumnos</a>
    <a href="../Controllers/registrar_asistencia.php"><i class="fas fa-calendar-check"></i> Registrar Asistencias</a>
    <a href="../Views/informe_asistencia.php"><i class="fas fa-chart-line"></i> Informe de Asistencias</a>
    <a href="../Controllers/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
</div>

<!-- Contenedor Principal -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4 bg-white">
                <div class="card-header text-white text-center p-4 rounded-top" style="background-color: #004d99;">
                    <h2 class="fw-bold">Agregar Nuevo Alumno</h2>
                </div>
                <div class="card-body">
                    <form id="form-agregar-alumno" method="POST">
                        <div class="mb-4">
                            <label for="nombre" class="form-label fs-4 text-dark">Nombre del Alumno:</label>
                            <input type="text" id="nombre" name="nombre" 
                                class="form-control fs-5 p-3 border-2 border-dark" 
                                required placeholder="Ingrese el nombre completo">
                        </div>

                        <div class="mb-4">
                            <label for="curso" class="form-label fs-4 text-dark">Curso:</label>
                            <input type="text" id="curso" name="curso" 
                                class="form-control fs-5 p-3 border-2 border-dark" 
                                required placeholder="Ingrese el nombre del curso">
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-primary btn-lg w-48 fs-5 p-3 rounded-3 transition-transform">
                                Guardar Alumno
                            </button>
                            <a href="../Views/lista_alumnos.php" 
                                class="btn btn-primary btn-lg w-48 fs-5 p-3 rounded-3 transition-transform">
                                Ver Lista de Alumnos
                            </a>
                        </div>
                    </form>
                    <div id="respuesta"></div> <!-- Mensaje de confirmación -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Incluir el footer
include_once "../Views/footer.php";
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Script para procesar el formulario sin recargar la página
    $(document).ready(function() {
        $('#form-agregar-alumno').submit(function(e) {
            e.preventDefault(); // Prevenir el envío normal del formulario

            // Recoger los datos del formulario
            var formData = $(this).serialize();

            // Hacer la solicitud AJAX
            $.ajax({
                type: 'POST',
                url: '../Controllers/guardar_alumno.php', // Ruta de tu controlador
                data: formData,
                success: function(response) {
                    // Mostrar el mensaje de éxito en la página sin recargar
                    $('#respuesta').html('<div class="alert alert-success">Alumno guardado exitosamente.</div>');
                    // Limpiar el formulario
                    $('#form-agregar-alumno')[0].reset();
                },
                error: function() {
                    $('#respuesta').html('<div class="alert alert-danger">Hubo un error al guardar el alumno. Intente nuevamente.</div>');
                }
            });
        });
    });
</script>
