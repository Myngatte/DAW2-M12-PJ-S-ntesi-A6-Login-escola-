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

// Obtener el parámetro 'materia' de la URL
if (isset($_GET['materia'])) {
    $materia = $_GET['materia'];
} else {
    // Si no se pasa el parámetro, redirigir a la página de notas
    header('Location: ./notas.php');
    exit();
}

// Consulta para obtener los detalles de los estudiantes y sus notas para la materia seleccionada
$sql = "
    SELECT 
        m.nombre_materia AS Materia,
        u.nom_usuario AS NombreUsuario,
        u.ape_usuario AS ApellidoUsuario,
        n.nota AS Nota
    FROM 
        tbl_materia m
    JOIN 
        tbl_notas n ON n.id_materia = m.id_materia
    JOIN 
        tbl_usuario u ON u.id_usuario = n.id_user
    WHERE 
        m.nombre_materia = ?
    ORDER BY n.nota DESC
";

// Preparar la consulta
$stmt = mysqli_prepare($conexion, $sql);

// Vincular el parámetro de la materia
mysqli_stmt_bind_param($stmt, 's', $materia);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

// Obtener el nombre del usuario
$sql_usuario = "SELECT nom_usuario FROM tbl_usuario WHERE id_usuario = ?";
$stmt_usuario = mysqli_prepare($conexion, $sql_usuario);

mysqli_stmt_bind_param($stmt_usuario, 'i', $id_usuario); 
mysqli_stmt_execute($stmt_usuario);
mysqli_stmt_bind_result($stmt_usuario, $nom_usuario);
mysqli_stmt_fetch($stmt_usuario);
mysqli_stmt_close($stmt_usuario);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Materia</title>
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

    <div class="container" style="margin-left: 260px;">
        <header>
            <h1>Notas de la Materia: <?php echo htmlspecialchars($materia); ?></h1>
        </header>

        <!-- Mostrar los detalles de la materia y las notas de los estudiantes -->
        <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>Nombre Completo</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($fila['Materia']); ?></td>
                            <td><?php echo htmlspecialchars($fila['NombreUsuario']); ?> <?php echo htmlspecialchars($fila['ApellidoUsuario']); ?></td>
                            <td><?php echo htmlspecialchars($fila['Nota']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No se encontraron detalles para esta materia.</p>
        <?php endif; ?>
    </div>
</body>
</html>
