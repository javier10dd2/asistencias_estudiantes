<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de Profesor
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'Profesor') {
    header("Location: login.php");
    exit();
}

include_once "../models/conexion.php";

// Obtener la lista de alumnos desde la base de datos
$sql = "SELECT id, nombre_alumno, curso FROM alumnos_asistencias WHERE fecha = CURDATE()";
$result = $conn->query($sql);
$alumnos = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $alumnos[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Asistencia</title>

    <!-- Incluir Font Awesome para los íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Enlazar el archivo CSS externo -->
    <link rel="stylesheet" href="../css/registrar_asistencia.css"> 
</head>

<body>

    <!-- Barra de Navegación -->
    <div class="navbar">
    <a href="../Views/profesor_dashboard.php"><i class="fas fa-home"></i> Inicio</a>
    <a href="../Controllers/agregar_alumno.php"><i class="fas fa-user-plus"></i> Agregar Alumnos</a>
    <a href="../Controllers/registrar_asistencia.php"><i class="fas fa-calendar-check"></i> Registrar Asistencias</a>
    <a href="../Views/informe_asistencia.php"><i class="fas fa-chart-line"></i> Informe de Asistencias</a>
    <a href="../Views/login.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>

    <div id="app" class="container" style="margin-top: 50px;">
        <div class="header">Registrar Asistencia</div>

        <!-- Formulario para seleccionar la fecha -->
        <div class="form-inline">
            <label for="date">Fecha:</label>
            <input @change="refreshAlumnosList" v-model="date" name="date" id="date" type="date" class="form-control">
            <button @click="save" class="btn-success">Guardar Asistencia</button>
        </div>

        <!-- Tabla de asistencia -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Curso</th>
                        <th>Asistencia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="alumno in alumnos" :key="alumno.id">
                        <td>{{ alumno.nombre_alumno }}</td>
                        <td>{{ alumno.curso }}</td>
                        <td>
                            <select v-model="alumno.estado" class="form-control">
                                <option value="">Sin seleccionar</option>
                                <option value="Asistió">Asistió</option>
                                <option value="Tardanza">Tardanza</option>
                                <option value="Faltó">Faltó</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                alumnos: <?php echo json_encode($alumnos); ?>,
                date: '<?php echo date("Y-m-d"); ?>'
            },
            methods: {
                refreshAlumnosList: function() {
                    // Puedes actualizar la lista de alumnos si es necesario
                },
                save: function() {
                    var formData = new FormData();
                    formData.append('date', this.date);
                    formData.append('alumnos', JSON.stringify(this.alumnos));

                    fetch('guardar_asistencia.php', {
                        method: 'POST',
                        body: formData
                    }).then(response => response.json())
                      .then(data => alert(data.message));
                }
            }
        });
    </script>

</body>

</html>




