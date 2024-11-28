<?php 
session_start();

// Verificar autenticación y rol de Profesor
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'Profesor') {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
include_once "../models/conexion.php";

$periodosResult = mysqli_query($conn, "SELECT * FROM periodos ORDER BY descripcion ASC");
$materiasResult = mysqli_query($conn, "SELECT * FROM materias ORDER BY nombre ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tarea_id = $_POST['tarea_id'] ?? null;
    $periodo_id = $_POST['periodo'] ?? null;
    $materia_id = $_POST['materia'] ?? null;
    $fecha_entrega = $_POST['fechaEntrega'] ?? null;
    $nombre = $_POST['tareaNombre'] ?? null;

    if ($periodo_id && $materia_id && $fecha_entrega && $nombre && strtotime($fecha_entrega) >= strtotime(date('Y-m-d'))) {
        if (isset($_POST['saveTarea'])) {
            $query = "INSERT INTO tareas (periodo_id, materia_id, fecha_entrega, nombre) VALUES ('$periodo_id', '$materia_id', '$fecha_entrega', '$nombre')";
        } elseif (isset($_POST['editTarea'])) {
            $query = "UPDATE tareas SET periodo_id = '$periodo_id', materia_id = '$materia_id', fecha_entrega = '$fecha_entrega', nombre = '$nombre' WHERE id = '$tarea_id'";
        } elseif (isset($_POST['deleteTarea'])) {
            $query = "DELETE FROM tareas WHERE id = '$tarea_id'";
        }

        if (mysqli_query($conn, $query)) {
            header("Location: tareas.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        $error_message = "La fecha de entrega no puede ser en el pasado o faltan campos.";
    }
}

$tareasResult = mysqli_query($conn, "SELECT t.id, t.nombre, t.fecha_entrega, t.periodo_id, t.materia_id, p.descripcion AS periodo, m.nombre AS materia 
    FROM tareas t 
    JOIN periodos p ON t.periodo_id = p.id 
    JOIN materias m ON t.materia_id = m.id");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/tareas.css">
    <title>Registro de Tareas</title>
</head>
<body>
    <div class="navbar">
        <a href="../Views/profesor_dashboard.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="../Controllers/agregar_alumno.php"><i class="fas fa-user-plus"></i> Agregar Alumnos</a>
        <a href="../Controllers/registrar_asistencia.php"><i class="fas fa-calendar-check"></i> Registrar Asistencias</a>
        <a href="../Views/informe_asistencia.php"><i class="fas fa-chart-line"></i> Informe de Asistencias</a>
        <a href="../Views/login.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>

    <div class="container">
        <h2>Registro de Tareas</h2>
        <?php if (isset($error_message)) echo "<div class='error-message'>$error_message</div>"; ?>
        <button id="newTaskBtn" class="button">Nueva Tarea</button>
    </div>

    <div id="taskModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Registrar Tarea</h3>
            <form method="POST" action="tareas.php">
                <input type="hidden" name="tarea_id" id="tarea_id">
                <label for="periodo">Periodo:</label>
                <select name="periodo" id="periodo" required>
                    <option value="">Selecciona un periodo</option>
                    <?php while ($periodo = mysqli_fetch_assoc($periodosResult)) { 
                        echo "<option value='{$periodo['id']}'>{$periodo['descripcion']}</option>";
                    } ?>
                </select>
                <label for="materia">Materia:</label>
                <select name="materia" id="materia" required>
                    <option value="">Selecciona una materia</option>
                    <?php while ($materia = mysqli_fetch_assoc($materiasResult)) { 
                        echo "<option value='{$materia['id']}'>{$materia['nombre']}</option>";
                    } ?>
                </select>
                <label for="fechaEntrega">Fecha de Entrega:</label>
                <input type="date" name="fechaEntrega" id="fechaEntrega" required>
                <label for="tareaNombre">Nombre de la Tarea:</label>
                <input type="text" name="tareaNombre" id="tareaNombre" required>
                <button type="submit" name="saveTarea" class="save">Guardar</button>
                <button type="button" id="cancelTaskBtn" class="cancel">Cancelar</button>
            </form>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Periodo</th>
                <th>Materia</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($tarea = mysqli_fetch_assoc($tareasResult)) { ?>
            <tr>
                <td><?= $tarea['nombre'] ?></td>
                <td data-id="<?= $tarea['periodo_id'] ?>"><?= $tarea['periodo'] ?></td>
                <td data-id="<?= $tarea['materia_id'] ?>"><?= $tarea['materia'] ?></td>
                <td><?= $tarea['fecha_entrega'] ?></td>
                <td>
                    <button class="editBtn" data-id="<?= $tarea['id'] ?>"><i class="fas fa-edit"></i> Editar</button>
                    <form method="POST" action="tareas.php" style="display:inline;">
                        <input type="hidden" name="tarea_id" value="<?= $tarea['id'] ?>">
                        <button type="submit" name="deleteTarea" class="deleteBtn"><i class="fas fa-trash-alt"></i> Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <script>
        const modal = document.getElementById("taskModal"),
              newTaskBtn = document.getElementById("newTaskBtn"),
              cancelTaskBtn = document.getElementById("cancelTaskBtn"),
              closeModalBtn = document.querySelector(".close");
        const editButtons = document.querySelectorAll(".editBtn"),
              tareaIdField = document.getElementById("tarea_id"),
              periodoField = document.getElementById("periodo"),
              materiaField = document.getElementById("materia"),
              fechaField = document.getElementById("fechaEntrega"),
              nombreField = document.getElementById("tareaNombre");

        newTaskBtn.onclick = () => { 
            tareaIdField.value = ""; 
            periodoField.selectedIndex = 0; 
            materiaField.selectedIndex = 0; 
            fechaField.value = ""; 
            nombreField.value = ""; 
            modal.style.display = "block"; 
        };

        editButtons.forEach((btn) => { 
            btn.onclick = (e) => { 
                const tareaRow = e.target.closest("tr");
                tareaIdField.value = btn.dataset.id; 
                periodoField.value = tareaRow.children[1].dataset.id; 
                materiaField.value = tareaRow.children[2].dataset.id; 
                fechaField.value = tareaRow.children[3].innerText; 
                nombreField.value = tareaRow.children[0].innerText; 
                modal.style.display = "block"; 
            }; 
        });

        closeModalBtn.onclick = cancelTaskBtn.onclick = () => { modal.style.display = "none"; };
        window.onclick = (event) => { if (event.target === modal) modal.style.display = "none"; };
    </script>
</body>
</html>


