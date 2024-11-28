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
$message = "";

// Obtener la información del usuario para cargar en el formulario
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $sql = "SELECT * FROM tbl_usuario WHERE id_usuario = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . mysqli_error($conexion));
        }

        mysqli_stmt_bind_param($stmt, "i", $id_usuario);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            $usuario_escuela = htmlspecialchars($fila['usuario_escuela']);
            $nom_usuario = htmlspecialchars($fila['nom_usuario']);
            $ape_usuario = htmlspecialchars($fila['ape_usuario']);
            $telefono_usuario = htmlspecialchars($fila['telefono_usuario']);
            $fecha_nacimi_usuario = htmlspecialchars($fila['fecha_nacimi_usuario']);
            $sexo_usuario = htmlspecialchars($fila['sexo_usuario']);
        } else {
            echo "<p>No se encontró información para este usuario.</p>";
            echo '<a href="menu.php">Volver a la lista de usuarios</a>';
            exit();
        }

        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        exit();
    }
}

// Procesar el formulario cuando el usuario lo envíe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los datos del formulario
    $usuario_escuela = trim($_POST['usuario_escuela']);
    $nom_usuario = trim($_POST['nom_usuario']);
    $ape_usuario = trim($_POST['ape_usuario']);
    $telefono_usuario = trim($_POST['telefono_usuario']);
    $fecha_nacimi_usuario = trim($_POST['fecha_nacimi_usuario']);
    $sexo_usuario = $_POST['sexo_usuario'];

    // Validaciones básicas
    if (empty($usuario_escuela) || empty($nom_usuario) || empty($ape_usuario) || empty($telefono_usuario) || empty($fecha_nacimi_usuario) || empty($sexo_usuario)) {
        $message = "Todos los campos son obligatorios.";
    } else {
        try {
            // Actualizar la información del usuario
            $sql_update = "UPDATE tbl_usuario 
                           SET usuario_escuela = ?, nom_usuario = ?, ape_usuario = ?, telefono_usuario = ?, fecha_nacimi_usuario = ?, sexo_usuario = ? 
                           WHERE id_usuario = ?";
            $stmt_update = mysqli_prepare($conexion, $sql_update);

            if (!$stmt_update) {
                throw new Exception("Error al preparar la actualización: " . mysqli_error($conexion));
            }

            mysqli_stmt_bind_param($stmt_update, "ssssssi", $usuario_escuela, $nom_usuario, $ape_usuario, $telefono_usuario, $fecha_nacimi_usuario, $sexo_usuario, $id_usuario);

            if (mysqli_stmt_execute($stmt_update)) {
                $message = "Usuario actualizado correctamente.";
            } else {
                $message = "Error al actualizar el usuario: " . mysqli_error($conexion);
            }

            mysqli_stmt_close($stmt_update);
        } catch (Exception $e) {
            $message = "Error: " . htmlspecialchars($e->getMessage());
        }
    }
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="./css/editar.css">
</head>
<body>
    <div class="container">
        <h1>Editar Usuario</h1>

        <?php if ($message): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>

        <form action="editar_usuario.php?id=<?= $id_usuario ?>" method="POST">
            <label for="usuario_escuela">Usuario:</label>
            <input type="text" id="usuario_escuela" name="usuario_escuela" value="<?= $usuario_escuela ?>" required>

            <label for="nom_usuario">Nombre:</label>
            <input type="text" id="nom_usuario" name="nom_usuario" value="<?= $nom_usuario ?>" required>

            <label for="ape_usuario">Apellido:</label>
            <input type="text" id="ape_usuario" name="ape_usuario" value="<?= $ape_usuario ?>" required>

            <label for="telefono_usuario">Teléfono:</label>
            <input type="text" id="telefono_usuario" name="telefono_usuario" value="<?= $telefono_usuario ?>" required>

            <label for="fecha_nacimi_usuario">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimi_usuario" name="fecha_nacimi_usuario" value="<?= $fecha_nacimi_usuario ?>" required>

            <label for="sexo_usuario">Sexo:</label>
            <select id="sexo_usuario" name="sexo_usuario" required>
                <option value="M" <?= $sexo_usuario === 'M' ? 'selected' : '' ?>>Masculino</option>
                <option value="F" <?= $sexo_usuario === 'F' ? 'selected' : '' ?>>Femenino</option>
            </select>

            <button type="submit">Actualizar Usuario</button>
        </form>

        <a href="menu.php" class="btn">Volver a la lista de usuarios</a>
    </div>
</body>
</html>
