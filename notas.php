<?php
session_start();
require_once('./conexion.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ./index.php?error=sesiones');
    exit();
}

// Obtener el id_usuario desde la sesión
$id_usuario = $_SESSION['id_usuario'];

// Inicializar la variable de mensaje
$message = "";

// Realizar una consulta para obtener el nombre del usuario
$sql = "SELECT nom_usuario FROM tbl_usuario WHERE id_usuario = ?";
$stmt = mysqli_prepare($conexion, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id_usuario); 
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nom_usuario);

    if (!mysqli_stmt_fetch($stmt)) {
        $nom_usuario = "Usuario";
    }
    mysqli_stmt_close($stmt);
} else {
    $nom_usuario = "Usuario";
}

// Obtener los datos para los filtros
$materia_filter = isset($_GET['materia']) ? $_GET['materia'] : '';
$nombre_filter = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$apellido_filter = isset($_GET['apellido']) ? $_GET['apellido'] : '';

// Base query
$sql = "
    SELECT 
        m.nombre_materia AS Materia,
        u.nom_usuario AS NombreUsuario,
        u.ape_usuario AS ApellidoUsuario,
        n.nota AS MejorNota,
        ( 
            SELECT AVG(nota)
            FROM tbl_notas 
            WHERE id_materia = m.id_materia
        ) AS MediaNotas
    FROM 
        tbl_materia m
    JOIN 
        tbl_notas n ON n.id_materia = m.id_materia
    JOIN 
        tbl_usuario u ON u.id_usuario = n.id_user
    WHERE 
        n.id_user = (
            SELECT id_user
            FROM tbl_notas
            WHERE id_materia = m.id_materia
            ORDER BY nota DESC
            LIMIT 1
        )";

// Add filters only if they are set
$filters = [];
if ($materia_filter) {
    $filters[] = "m.nombre_materia LIKE ?";
}
if ($nombre_filter) {
    $filters[] = "u.nom_usuario LIKE ?";
}
if ($apellido_filter) {
    $filters[] = "u.ape_usuario LIKE ?";
}

// Append the filters to the SQL query
if (count($filters) > 0) {
    $sql .= " AND " . implode(" AND ", $filters);
}

$sql .= " ORDER BY MediaNotas DESC;";

// Prepare the statement
$stmt = mysqli_prepare($conexion, $sql);

// Define the parameters
$params = [];
$param_types = '';
if ($materia_filter) {
    $params[] = "%" . $materia_filter . "%";
    $param_types .= 's'; // 's' for string
}
if ($nombre_filter) {
    $params[] = "%" . $nombre_filter . "%";
    $param_types .= 's';
}
if ($apellido_filter) {
    $params[] = "%" . $apellido_filter . "%";
    $param_types .= 's';
}

// Bind the parameters if any
if (count($params) > 0) {
    mysqli_stmt_bind_param($stmt, $param_types, ...$params);
}

// Ejecutar la consulta
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas de Estudiantes</title>
    <link rel="stylesheet" href="./css/menu.css">
</head>
<body>
    <div class="sidebar">
        <img src="./images/profile.png" alt="Admin">
        <h3><?php echo htmlspecialchars($nom_usuario); ?></h3>
        <span>Admin</span>
        <a href="./menu.php">Estudiantes</a>
        <a href="./notas.php">Notas</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <!-- Contenido principal -->
    <div class="container" style="margin-left: 260px;">
        <header>
            <h1>Mejor Alumno por Asignatura</h1>
        </header>

        <!-- Filtros -->
        <form method="get" action="notas.php">
            <div class="filters">
                <label for="materia">Materia:</label>
                <input type="text" name="materia" id="materia" value="<?php echo $materia_filter; ?>">
                <button type="submit">Filtrar</button>
                <!-- Botón para eliminar los filtros -->
                <form method="get" action="notas.php" style="margin-top: 10px;">
                    <button type="submit" name="clear_filters" value="true">Eliminar filtros</button>
                </form>
            </div>
        </form>

        <?php
        // Verificar si el botón de eliminar filtros ha sido presionado
        if (isset($_GET['clear_filters'])) {
            // Recargar la página sin filtros
            header("Location: notas.php");
            exit();
        }
        ?>

        <!-- Mensajes -->
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Mostrar al mejor alumno por asignatura -->
        <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>Nombre Completo</th>
                        <th>Mejor Nota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($fila['Materia']); ?></td>
                            <td><?php echo htmlspecialchars($fila['NombreUsuario']); ?> <?php echo htmlspecialchars($fila['ApellidoUsuario']); ?></td>
                            <td><?php echo htmlspecialchars($fila['MejorNota']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No se encontraron resultados con los filtros seleccionados.</p>
        <?php endif; ?>
    </div>
</body>
</html>
