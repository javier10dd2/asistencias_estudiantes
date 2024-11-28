<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de Profesor
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'Profesor') {
    header("Location: login.php");
    exit();
}

// Incluir archivos comunes
include_once "header.php";
include_once "nav.php";

// Mostrar mensaje de éxito si está presente
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<div class='alert alert-success text-center'>Alumno guardado correctamente.</div>";
}
?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center">Agregar Nuevo Alumno</h1>
        </div>

        <div class="col-12">
            <!-- Formulario para agregar un nuevo alumno -->
            <form action="guardar_alumno.php" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Alumno:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required placeholder="Ingrese el nombre completo">
                </div>

                <div class="mb-3">
                    <label for="curso" class="form-label">Curso:</label>
                    <input type="text" id="curso" name="curso" class="form-control" required placeholder="Ingrese el nombre del curso">
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
</div>

<?php
include_once "footer.php";
?>
