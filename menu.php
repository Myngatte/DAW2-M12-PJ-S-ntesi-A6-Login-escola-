<?php
session_start();
require_once('./conexion.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ./index.php?error=sesiones');
    exit();
}

// Variable para mensajes
$message = "";

// Manejar la actualización de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $id_usuario = filter_input(INPUT_POST, 'id_usuario', FILTER_VALIDATE_INT);
    $usuario_escuela = htmlspecialchars($_POST['usuario_escuela'], ENT_QUOTES, 'UTF-8');
    $nom_usuario = htmlspecialchars($_POST['nom_usuario'], ENT_QUOTES, 'UTF-8');
    $ape_usuario = htmlspecialchars($_POST['ape_usuario'], ENT_QUOTES, 'UTF-8');
    $telefono_usuario = htmlspecialchars($_POST['telefono_usuario'], ENT_QUOTES, 'UTF-8');
    $fecha_nacimi_usuario = htmlspecialchars($_POST['fecha_nacimi_usuario'], ENT_QUOTES, 'UTF-8');
    $sexo_usuario = htmlspecialchars($_POST['sexo_usuario'], ENT_QUOTES, 'UTF-8');

    if ($id_usuario && $usuario_escuela && $nom_usuario && $ape_usuario && $telefono_usuario && $fecha_nacimi_usuario && $sexo_usuario) {
        $sql = "UPDATE tbl_usuario SET usuario_escuela = ?, nom_usuario = ?, ape_usuario = ?, telefono_usuario = ?, fecha_nacimi_usuario = ?, sexo_usuario = ? WHERE id_usuario = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssssi", $usuario_escuela, $nom_usuario, $ape_usuario, $telefono_usuario, $fecha_nacimi_usuario, $sexo_usuario, $id_usuario);
            if (mysqli_stmt_execute($stmt)) {
                $message = "Usuario actualizado correctamente.";
            } else {
                $message = "Error al actualizar el usuario: " . mysqli_error($conexion);
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        $message = "Por favor, completa todos los campos correctamente.";
    }
}

// Manejar la eliminación de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $id_usuario = filter_input(INPUT_POST, 'id_usuario', FILTER_VALIDATE_INT);

    if ($id_usuario) {
        $sql = "DELETE FROM tbl_usuario WHERE id_usuario = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id_usuario);
            if (mysqli_stmt_execute($stmt)) {
                $message = "Usuario eliminado correctamente.";
            } else {
                $message = "Error al eliminar el usuario: " . mysqli_error($conexion);
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        $message = "ID de usuario no válido.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/menu.css">
    <title>Gestión de Usuarios</title>
</head>
<body>
    <div class="container">
        <h1>Gestión de Usuarios</h1>
        <div class="acciones">
            <a href="añadir_usuario.php" class="btn btn-primary">Añadir Usuario</a>
        </div>
        <?php
        try {
            // Preparar la consulta
            $sql = "SELECT * FROM tbl_usuario";
            $stmt = mysqli_prepare($conexion, $sql);
            
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . mysqli_error($conexion));
            }

            // Ejecutar la consulta
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);

            // Verificar si hay resultados
            if ($resultado && mysqli_num_rows($resultado) > 0) {
                echo "<table>
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Nombre completo</th>
                            <th>Teléfono</th>
                            <th>Fecha de nacimiento</th>
                            <th>Sexo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>";

                // Iterar sobre los resultados
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>
                            <td>
                                <a href='usuario.php?id=" . urlencode($fila['id_usuario']) . "'>" . 
                                htmlspecialchars($fila['usuario_escuela']) . 
                                "</a>
                            </td>
                            <td>" . htmlspecialchars($fila['nom_usuario']) . " " . htmlspecialchars($fila['ape_usuario']) . "</td>
                            <td>" . htmlspecialchars($fila['telefono_usuario']) . "</td>
                            <td>" . htmlspecialchars($fila['fecha_nacimi_usuario']) . "</td>
                            <td>" . htmlspecialchars($fila['sexo_usuario']) . "</td>
                            <td>
                                <a href='editar_usuario.php?id=" . urlencode($fila['id_usuario']) . "'>Editar</a>
                                <a href='eliminar_usuario.php?id=" . urlencode($fila['id_usuario']) . "' onclick=\"return confirm('¿Estás seguro de que deseas eliminar este usuario?');\">Eliminar</a>
                            </td>
                          </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No se encontraron usuarios.</p>";
            }

            // Cerrar la consulta y conexión
            mysqli_stmt_close($stmt);
            mysqli_close($conexion);

        } catch (Exception $e) {
            echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>
    </div>
</body>
</html>
