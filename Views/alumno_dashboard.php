<?php 
session_start();

// Verificar si el usuario está autenticado y tiene el rol de Alumno
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'Alumno') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Alumno</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
            border-bottom: 3px solid #0056b3;
        }

        .navbar {
            display: flex;
            justify-content: center;
            background-color: #343a40;
            padding: 15px;
            border-bottom: 3px solid #444;
        }

        .navbar a {
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            text-align: center;
            margin: 0 15px;
            border-radius: 5px;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .navbar a:hover {
            background-color: #007bff;
        }

        .container {
            margin: 20px;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dashboard-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 20px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            width: 100%;
        }

        .card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-icon {
            font-size: 40px;
            margin-bottom: 15px;
            color: #007bff;
        }

        .card-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-description {
            font-size: 16px;
            margin-bottom: 15px;
            text-align: justify;
            line-height: 1.6;
            flex-grow: 1;
        }

        .card button {
            background-color: #007bff;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 5px;
            transition: background-color 0.3s ease;
        }

        .card button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="header">
        Bienvenido Alumno
    </div>

    <div class="navbar">
        <a href="../Views/alumno_dashboard.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="../Controllers/consultar_asistencia.php"><i class="fas fa-calendar-check"></i> Consultar Asistencias</a>
        <a href="../Views/login.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>

    <div class="container">
        <div class="dashboard-container">
            <div class="dashboard-grid">
                <!-- Tarjeta de Tareas -->
                <div class="card">
                    <div class="card-icon"><i class="fas fa-tasks"></i></div>
                    <div class="card-title">Tareas Asignadas</div>
                    <div class="card-description">
                        Revisa las tareas asignadas para mantenerte al día con tus actividades académicas.
                    </div>
                    <button onclick="window.location.href='../Controllers/ver_tareas.php'">Ver Tareas</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>


