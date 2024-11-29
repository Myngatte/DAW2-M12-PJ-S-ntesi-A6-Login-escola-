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
    echo '<a href="menu.php" class="btn">Volver a la lista de usuarios</a>';
    exit();
}

$id_usuario = intval($_GET['id']);
$mensaje = "";

try {
    // Obtener información del usuario
    $sql_usuario = "SELECT usuario_escuela, nom_usuario, ape_usuario, telefono_usuario, fecha_nacimi_usuario, sexo_usuario, foto_usuario
                    FROM tbl_usuario 
                    WHERE id_usuario = ?";
    $stmt_usuario = mysqli_prepare($conexion, $sql_usuario);

    if (!$stmt_usuario) {
        throw new Exception("Error al preparar la consulta: " . mysqli_error($conexion));
    }

    mysqli_stmt_bind_param($stmt_usuario, "i", $id_usuario);
    mysqli_stmt_execute($stmt_usuario);
    $resultado_usuario = mysqli_stmt_get_result($stmt_usuario);

    if ($fila = mysqli_fetch_assoc($resultado_usuario)) {
        $usuario = htmlspecialchars($fila['usuario_escuela']);
        $nombre_completo = htmlspecialchars($fila['nom_usuario']) . " " . htmlspecialchars($fila['ape_usuario']);
        $telefono = htmlspecialchars($fila['telefono_usuario']);
        $fecha_nacimiento = htmlspecialchars($fila['fecha_nacimi_usuario']);
        $sexo = htmlspecialchars($fila['sexo_usuario']);
        $foto_usuario = htmlspecialchars($fila['foto_usuario']); // Solo el nombre del archivo de la foto
    } else {
        echo "<p>No se encontró información para este usuario.</p>";
        echo '<a href="menu.php" class="btn">Volver a la lista de usuarios</a>';
        exit();
    }

    mysqli_stmt_close($stmt_usuario);

    // Consultar las materias y notas del usuario
    $sql_notas = "SELECT m.id_materia, m.nombre_materia, n.nota 
                  FROM tbl_materia m
                  LEFT JOIN tbl_notas n ON m.id_materia = n.id_materia AND n.id_user = ?";
    $stmt_notas = mysqli_prepare($conexion, $sql_notas);

    if (!$stmt_notas) {
        throw new Exception("Error al preparar la consulta de notas: " . mysqli_error($conexion));
    }

    mysqli_stmt_bind_param($stmt_notas, "i", $id_usuario);
    mysqli_stmt_execute($stmt_notas);
    $resultado_notas = mysqli_stmt_get_result($stmt_notas);

    $materias_notas = [];
    while ($fila = mysqli_fetch_assoc($resultado_notas)) {
        $materias_notas[] = [
            'id_materia' => htmlspecialchars($fila['id_materia']),
            'materia' => htmlspecialchars($fila['nombre_materia']),
            'nota' => $fila['nota'] !== null ? htmlspecialchars($fila['nota']) : '',
        ];
    }

    mysqli_stmt_close($stmt_notas);
    mysqli_close($conexion);

} catch (Exception $e) {
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo '<a href="menu.php" class="btn">Volver a la lista de usuarios</a>';
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

        <!-- Mostrar la imagen del usuario y la información al lado -->
        <div class="user-info">
            <div class="user-image">
                <!-- Concatenar el prefijo 'img/' con el nombre de la imagen -->
                <img src="img/<?php echo $foto_usuario ? $foto_usuario : 'default.png'; ?>" alt="Imagen de usuario" width="150">
            </div>
            <div class="user-details">
                <p><strong>Usuario:</strong> <?= $usuario ?></p>
                <p><strong>Nombre Completo:</strong> <?= $nombre_completo ?></p>
                <p><strong>Teléfono:</strong> <?= $telefono ?></p>
                <p><strong>Fecha de Nacimiento:</strong> <?= $fecha_nacimiento ?></p>
                <p><strong>Sexo:</strong> <?= $sexo ?></p>
            </div>
        </div>

        <h2>Materias y Notas</h2>
        <?php if ($mensaje): ?>
            <p class="message"><strong><?= $mensaje ?></strong></p>
        <?php endif; ?>

        <form method="POST" class="notas-form">
            <table>
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materias_notas as $nota_alumno): ?>
                        <tr>
                            <td><?= $nota_alumno['materia'] ?></td>
                            <td>
                                <!-- Cada materia tiene un campo de entrada para su nota -->
                                <input type="number" step="0.01" name="notas[<?= $nota_alumno['id_materia'] ?>]" value="<?= $nota_alumno['nota'] ?>" placeholder="Ingrese nota" class="input-note" min="0" max="10">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn">Guardar Cambios</button>
        </form>

        <a href="menu.php" class="btn">Volver a la lista de usuarios</a>
    </div>
</body>
</html>
