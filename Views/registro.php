<?php
$servidor = 'localhost';
$usuario = 'root';
$clave = '';
$baseDatos = 'pag_asistencias';
$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDatos);

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    $tipo = $_POST['tipo'];
    $telefono = $_POST['telefono']; // Nuevo campo teléfono

    // Validar si el correo ya está registrado
    $consultaCorreo = "SELECT * FROM usuarios WHERE correo='$correo'";
    $resultadoCorreo = mysqli_query($enlace, $consultaCorreo);

    if (mysqli_num_rows($resultadoCorreo) > 0) {
        echo "<script>alert('El correo ya está registrado. Intente con otro.');</script>";
    } else {
        // Insertar datos en la base de datos
        $consultaInsertar = "INSERT INTO usuarios (nombre, correo, contrasena, tipo, telefono) 
                             VALUES ('$nombre', '$correo', '$contrasena', '$tipo', '$telefono')";
        if (mysqli_query($enlace, $consultaInsertar)) {
            echo "<script>alert('Registro exitoso. Ahora puede iniciar sesión.'); window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('Error al registrar. Por favor, intente nuevamente.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | Página de Asistencias</title>
    <!-- Incluir el archivo CSS de estilos -->
    <link rel="stylesheet" href="../css/styles_registro.css"> <!-- Ruta de tu archivo CSS -->
</head>
<body>
    <header>
        <h1>Registro de Usuario</h1>
    </header>

    <div class="container">
        <!-- Área de imagen dinámica -->
        <div class="imagen-container">
            <img id="imagenRol" src="../imagenes/user.png" alt="Imagen por Defecto" />
        </div>

        <form action="registro.php" method="post">
            <input type="text" name="nombre" placeholder="Nombre completo" required>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <input type="text" name="telefono" placeholder="Teléfono (máximo 9 caracteres)" maxlength="9" required> <!-- Campo teléfono -->
            
            <select name="tipo" required>
                <option value="">Selecciona tu rol</option>
                <option value="Profesor">Profesor</option>
                <option value="Alumno">Alumno</option>
            </select>
            
            <input type="submit" value="Registrarse">
        </form>
        
        <h3>¿Ya tienes una cuenta?</h3>
        <button class="btn-iniciar-sesion">
            <a href="login.php">Iniciar sesión</a>
        </button>
    </div>

    <footer>
        <p>© 2024 Sistema de Asistencias Estudiantiles</p>
    </footer>
</body>
</html>
