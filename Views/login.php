<?php
$servidor = 'localhost';
$usuario = 'root';
$clave = '';
$baseDatos = 'pag_asistencias';
$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDatos);

session_start();

if (isset($_POST['login'])) {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    $tipo = $_POST['tipo'];

    // Consulta para obtener el usuario y verificar la contraseña
    $consulta = "SELECT * FROM usuarios WHERE correo='$correo' AND contrasena='$contrasena' AND tipo='$tipo'";
    $resultado = mysqli_query($enlace, $consulta);

    if (mysqli_num_rows($resultado) > 0) {
        $_SESSION['correo'] = $correo;
        $_SESSION['tipo'] = $tipo;

        // Redirige al dashboard correspondiente dependiendo del rol
        if ($tipo == 'Profesor') {
            header("Location: profesor_dashboard.php");
        } elseif ($tipo == 'Alumno') {
            header("Location: alumno_dashboard.php");
        } else {
            echo "<script>alert('Rol desconocido.'); window.location.href = 'login.php';</script>";
        }
        exit;
    } else {
        echo "<script>alert('Usuario, contraseña o tipo incorrecto. Por favor, intente nuevamente.'); window.location.href = 'login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Página de Asistencias</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        // Función para cambiar la imagen según el rol seleccionado
        function cambiarImagen() {
            const selectRol = document.querySelector('select[name="tipo"]');
            const imagenRol = document.getElementById('imagenRol');

            // Cambiar la imagen en función del rol seleccionado
            switch (selectRol.value) {
                case "Profesor":
                    imagenRol.src = "../imagenes/user_profesor.png";
                    imagenRol.alt = "Imagen de Profesor";
                    break;
                case "Alumno":
                    imagenRol.src = "../imagenes/user_estudiante.png";
                    imagenRol.alt = "Imagen de Alumno";
                    break;
                default:
                    imagenRol.src = "../imagenes/user.png";
                    imagenRol.alt = "Imagen del Usuario";
                    break;
            }
        }
    </script>
    <style>
        /* Estructura general */
        body {
            background-color: #e0f7fa; /* Fondo celeste claro */
            font-family: Arial, sans-serif;
            color: #333333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #0047ab; /* Azul oscuro */
            color: #ffffff;
            padding: 1em;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .container {
            max-width: 400px;
            margin: 40px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .imagen-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        #imagenRol {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
        }

        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #cccccc;
            border-radius: 30px;
            font-size: 1em;
            background-color: #f7f9fb;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #0047ab;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #003380;
        }

        h3 {
            font-size: 0.9em;
            color: #333333;
        }

        .btn-registrar {
            margin-top: 5px;
            padding: 10px;
            background-color: #ffa726;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .btn-registrar a {
            color: #ffffff;
            text-decoration: none;
        }

        .btn-registrar:hover {
            background-color: #ffb74d;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #0047ab;
            color: #ffffff;
            position: fixed;
            width: 100%;
            bottom: 0;
            font-size: 0.8em;
        }

        .g-recaptcha {
            margin: 20px auto;
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <header>
        <h1> Página de Asistencias </h1>
    </header>

    <div class="container">
        <div class="imagen-container">
            <img id="imagenRol" src="../imagenes/user.png" alt="Imagen del Usuario">
        </div>
        <form action="login.php" method="post">
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <select name="tipo" required onchange="cambiarImagen()">
                <option value="">Selecciona tu rol</option>
                <option value="Profesor">Profesor</option>
                <option value="Alumno">Alumno</option>
            </select>
            <div class="g-recaptcha" data-sitekey="6LcyvYoqAAAAAInQ-KrYxJ2hA04s2otOsRiEnOuk"></div>
            <input type="submit" name="login" value="Iniciar sesión">
            <h3>¿No tienes una cuenta?</h3>
            <button class="btn-registrar"><a href="registro.php">Regístrate</a></button>
        </form>
    </div>

    <footer>
        <p>© 2024 Sistema de Asistencias Estudiantiles</p>
    </footer>
</body>
</html>
