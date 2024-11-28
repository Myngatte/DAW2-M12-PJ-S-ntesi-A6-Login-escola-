<?php
session_start();
require_once('./conexion.php');

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ./index.php?error=sesiones');
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_usuario = intval($_GET['id']);

    $sql = "SELECT * FROM tbl_usuario WHERE id_usuario = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_usuario);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        $usuario_escuela = htmlspecialchars($fila['usuario_escuela']);
        $nombre_completo = htmlspecialchars($fila['nom_usuario']) . " " . htmlspecialchars($fila['ape_usuario']);
    } else {
        echo "<p>No se encontró el usuario.</p>";
        exit();
    }

    mysqli_stmt_close($stmt);
} else {
    echo "<p>ID de usuario no válido.</p>";
    exit();
}

if (isset($_POST['confirmar_eliminar'])) {
    try {
        mysqli_begin_transaction($conexion);

        $sql_notas = "DELETE FROM tbl_notas WHERE id_user = ?";
        $stmt_notas = mysqli_prepare($conexion, $sql_notas);
        mysqli_stmt_bind_param($stmt_notas, "i", $id_usuario);

        if (!mysqli_stmt_execute($stmt_notas)) {
            throw new Exception("Error al eliminar las notas relacionadas: " . mysqli_error($conexion));
        }

        mysqli_stmt_close($stmt_notas);

        $sql_usuario = "DELETE FROM tbl_usuario WHERE id_usuario = ?";
        $stmt_usuario = mysqli_prepare($conexion, $sql_usuario);
        mysqli_stmt_bind_param($stmt_usuario, "i", $id_usuario);

        if (!mysqli_stmt_execute($stmt_usuario)) {
            throw new Exception("Error al eliminar el usuario: " . mysqli_error($conexion));
        }

        mysqli_stmt_close($stmt_usuario);

        mysqli_commit($conexion);

        header('Location: menu.php?mensaje=Usuario eliminado correctamente');
        exit();

    } catch (Exception $e) {
        mysqli_rollback($conexion);
        echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuario</title>
    <link rel="stylesheet" href="./css/eliminar.css">
</head>
<body>

    <div class="container">
        <h1>Eliminar Usuario</h1>
        <p>¿Estás seguro de que deseas eliminar al usuario <strong><?php echo $usuario_escuela; ?></strong> (<?php echo $nombre_completo; ?>)?</p>

        <form action="eliminar_usuario.php?id=<?php echo $id_usuario; ?>" method="post">
            <button type="submit" name="confirmar_eliminar" class="confirmar-btn">Confirmar Eliminación</button>
            <a href="menu.php" class="cancelar-btn">Cancelar</a>
        </form>
    </div>

</body>
</html>