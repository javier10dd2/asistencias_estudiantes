<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de Profesor
if (!isset($_SESSION['correo']) || $_SESSION['tipo'] !== 'Profesor') {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
include_once "../models/conexion.php";

// Mensajes de estado
$message = "";

// Verificar si se envió un formulario para agregar, editar o eliminar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['descripcion'])) {
        // Si el formulario tiene un campo 'descripcion', es para agregar un nuevo período
        $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']); // Escapar la entrada
        
        // Lógica para guardar el período en la base de datos
        $query = "INSERT INTO periodos (descripcion) VALUES ('$descripcion')";
        mysqli_query($conn, $query);
    }

    // Editar período
    if (isset($_POST['editar_id'])) {
        $id = $_POST['editar_id'];
        $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']); // Escapar la entrada

        $query = "UPDATE periodos SET descripcion='$descripcion' WHERE id='$id'";
        mysqli_query($conn, $query);
    }

    // Eliminar período
    if (isset($_POST['eliminar_id'])) {
        $id = $_POST['eliminar_id'];

        // Comprobación de seguridad: confirmar que el ID existe
        $query_check = "SELECT * FROM periodos WHERE id='$id'";
        $result_check = mysqli_query($conn, $query_check);
        if (mysqli_num_rows($result_check) > 0) {
            // Eliminar el período
            $query = "DELETE FROM periodos WHERE id='$id'";
            mysqli_query($conn, $query);
        }
    }
}

// Obtener los períodos existentes (si los hay)
$query = "SELECT * FROM periodos";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Períodos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
    <link rel="stylesheet" href="../css/periodos.css">
</head>
<body class="bg-gray-100">
    <!-- Barra de Navegación -->
    <div class="navbar">
         <a href="../Views/profesor_dashboard.php"><i class="fas fa-home"></i> Inicio</a>
         <a href="../Controllers/agregar_alumno.php"><i class="fas fa-user-plus"></i> Agregar Alumnos</a>
         <a href="../Controllers/registrar_asistencia.php"><i class="fas fa-calendar-check"></i> Registrar Asistencias</a>
         <a href="../Views/informe_asistencia.php"><i class="fas fa-chart-line"></i> Informe de Asistencias</a>
         <a href="../Views/login.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>

    <!-- Contenedor Principal -->
    <div class="container mx-auto p-6 mt-24">
        <!-- Título -->
        <h1 class="text-3xl font-semibold mb-4 text-purple-700">Períodos</h1>
        
        <!-- Botón para abrir el modal de nuevo período -->
        <button 
            class="px-5 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-300 mb-4"
            onclick="document.getElementById('modal').classList.remove('hidden')">
            Nuevo Período
        </button>

        <!-- Tabla de Períodos -->
        <table class="table-auto w-full border-collapse mb-4 bg-white shadow-md rounded-lg">
            <thead>
                <tr>
                    <th class="border-b py-3 px-5 text-left text-gray-700">ID</th>
                    <th class="border-b py-3 px-5 text-left text-gray-700">Descripción</th>
                    <th class="border-b py-3 px-5 text-left text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr class="hover:bg-gray-100 transition-all">
                        <td class="border-b py-3 px-5"><?php echo $row['id']; ?></td>
                        <td class="border-b py-3 px-5"><?php echo $row['descripcion']; ?></td>
                        <td class="border-b py-3 px-5">
                            <button onclick="openEditModal(<?php echo $row['id']; ?>, '<?php echo $row['descripcion']; ?>')" 
                                class="bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 transition-all duration-300">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <form action="periodos.php" method="POST" class="inline-block" onsubmit="return confirmDelete()">
                                <input type="hidden" name="eliminar_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" 
                                    class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-all duration-300">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para agregar/editar período -->
    <div id="modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="modal-container">
            <h2 id="modalTitle" class="text-2xl font-semibold mb-4">Nuevo Período</h2>
            <form action="periodos.php" method="POST">
                <div class="mb-4">
                    <label for="descripcion" class="block text-gray-700">Descripción del Período:</label>
                    <input type="text" name="descripcion" id="descripcion" class="w-full px-4 py-2 border rounded-md" required>
                    <input type="hidden" name="editar_id" id="editId">
                </div>
                <div class="flex justify-end space-x-4">
                    <button 
                        type="button" 
                        class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-all duration-300"
                        onclick="document.getElementById('modal').classList.add('hidden')">
                        Cancelar
                    </button>
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-all duration-300">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, descripcion) {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Editar Período';
            document.getElementById('descripcion').value = descripcion;
            document.getElementById('editId').value = id;
        }

        function confirmDelete() {
            return confirm("¿Estás seguro de que deseas eliminar este período?");
        }
    </script>
</body>
</html>
