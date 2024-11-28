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
$mensaje = "";

try {
    // Obtener información del usuario
    $sql_usuario = "SELECT usuario_escuela, nom_usuario, ape_usuario, telefono_usuario, fecha_nacimi_usuario, sexo_usuario 
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
    } else {
        echo "<p>No se encontró información para este usuario.</p>";
        echo '<a href="menu.php">Volver a la lista de usuarios</a>';
        exit();
    }

    mysqli_stmt_close($stmt_usuario);

    // Procesar actualización de notas
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_materia']) && isset($_POST['nota'])) {
        $id_materia = intval($_POST['id_materia']);
        $nota = floatval($_POST['nota']);

        if ($nota >= 0) {
            // Verificar si la nota ya existe
            $sql_check = "SELECT id_notas FROM tbl_notas WHERE id_user = ? AND id_materia = ?";
            $stmt_check = mysqli_prepare($conexion, $sql_check);
            mysqli_stmt_bind_param($stmt_check, "ii", $id_usuario, $id_materia);
            mysqli_stmt_execute($stmt_check);
            $resultado_check = mysqli_stmt_get_result($stmt_check);

            if ($fila = mysqli_fetch_assoc($resultado_check)) {
                // Actualizar nota existente
                $sql_update = "UPDATE tbl_notas SET nota = ? WHERE id_user = ? AND id_materia = ?";
                $stmt_update = mysqli_prepare($conexion, $sql_update);
                mysqli_stmt_bind_param($stmt_update, "dii", $nota, $id_usuario, $id_materia);
                mysqli_stmt_execute($stmt_update);
                $mensaje = "Nota actualizada correctamente.";
            } else {
                // Insertar nueva nota
                $sql_insert = "INSERT INTO tbl_notas (id_user, id_materia, nota) VALUES (?, ?, ?)";
                $stmt_insert = mysqli_prepare($conexion, $sql_insert);
                mysqli_stmt_bind_param($stmt_insert, "iid", $id_usuario, $id_materia, $nota);
                mysqli_stmt_execute($stmt_insert);
                $mensaje = "Nota añadida correctamente.";
            }

            mysqli_stmt_close($stmt_check);
        } else {
            $mensaje = "Datos inválidos.";
        }
    }

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

        <h2>Materias y Notas</h2>
        <?php if ($mensaje): ?>
            <p><strong><?= $mensaje ?></strong></p>
        <?php endif; ?>

        <form method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>Nota</th>
                        <th>Guardar Cambios</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materias_notas as $nota_alumno): ?>
                        <tr>
                            <td><?= $nota_alumno['materia'] ?></td>
                            <td>
                                <!-- Cada materia tiene un formulario individual con su propio campo de nota -->
                                <form method="POST">
                                    <input type="hidden" name="id_materia" value="<?= $nota_alumno['id_materia'] ?>">
                                    <input type="number" step="0.01" name="nota" value="<?= $nota_alumno['nota'] ?>" placeholder="Ingrese nota">
                                    <br>
                                    <button type="submit" class="edit-btn" title="Guardar Cambios">
                                        <img src="./Imagenes/boton_editar.png" alt="Guardar" class="edit-icon">
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>

        <a href="menu.php" class="btn">Volver a la lista de usuarios</a>
    </div>
</body>
</html>
