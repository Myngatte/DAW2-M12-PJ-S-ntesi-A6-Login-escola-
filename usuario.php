<?php
session_start();
require_once('./conexion.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ./index.php?error=sesiones');
    exit();
}

// Verificar si se recibió un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>ID de usuario no válido.</p>";
    echo '<a href="menu.php">Volver a la lista de usuarios</a>';
    exit();
}

$id_usuario = intval($_GET['id']);

try {
    // Preparar la consulta para obtener información del usuario
    $sql = "SELECT usuario_escuela, nom_usuario, ape_usuario, telefono_usuario, fecha_nacimi_usuario, sexo_usuario 
            FROM tbl_usuario 
            WHERE id_usuario = ?";
    $stmt = mysqli_prepare($conexion, $sql);

    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . mysqli_error($conexion));
    }

    mysqli_stmt_bind_param($stmt, "i", $id_usuario);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    // Verificar si hay resultados
    if ($fila = mysqli_fetch_assoc($resultado)) {
        // Asignar los datos a variables para mejorar legibilidad
        $usuario = htmlspecialchars($fila['usuario_escuela']);
        $nombre_completo = htmlspecialchars($fila['nom_usuario']) . " " . htmlspecialchars($fila['ape_usuario']);
        $telefono = htmlspecialchars($fila['telefono_usuario']);
        $fecha_nacimiento = htmlspecialchars($fila['fecha_nacimi_usuario']);
        $sexo = htmlspecialchars($fila['sexo_usuario']);
    } else {
        echo "<p>No se encontró información para este usuario.</p>";
        echo '<a href="menu.php">Volver a la lista de usuarios</a>';
        exit();
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);

} catch (Exception $e) {
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo '<a href="menu.php">Volver a la lista de usuarios</a>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Usuario</title>
    <link rel="stylesheet" href="./css/usuario.css">
</head>
<body>
    <div class="container">
        <h1>Detalle del Usuario</h1>
        <p><strong>Usuario:</strong> <?= $usuario ?></p>
        <p><strong>Nombre Completo:</strong> <?= $nombre_completo ?></p>
        <p><strong>Teléfono:</strong> <?= $telefono ?></p>
        <p><strong>Fecha de Nacimiento:</strong> <?= $fecha_nacimiento ?></p>
        <p><strong>Sexo:</strong> <?= $sexo ?></p>
        <a href="menu.php" class="btn">Volver a la lista de usuarios</a>
    </div>
</body>
</html>
