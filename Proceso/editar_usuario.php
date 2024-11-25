<?php
require_once('../conexion.php');
session_start();

$usuario = null;

if (isset($_GET['id'])) {
    $id = $_GET['id']; 
    try {
        $sql = "SELECT * FROM tbl_usuario WHERE id_usuario = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            $resultado = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($resultado) > 0) {
                $usuario = mysqli_fetch_assoc($resultado);
            } else {
                header("Location: ../menu.php?error=usuario_no_encontrado");
                exit();
            }
        } else {
            throw new Exception("Error al ejecutar la consulta.");
        }

        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        header("Location: ../menu.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: ../menu.php?error=usuario_no_encontrado");
    exit();
}

if (isset($_POST['btn_editar_usuario'])) {
    $nombre_usuario = mysqli_real_escape_string($conexion, htmlspecialchars($_POST['nombre_usuario']));
    $nombre_real = mysqli_real_escape_string($conexion, htmlspecialchars($_POST['nombre_real']));
    $ape_usuario = mysqli_real_escape_string($conexion, htmlspecialchars($_POST['ape_usuario']));
    $telefono = mysqli_real_escape_string($conexion, htmlspecialchars($_POST['telefono']));
    $fecha_nacimiento = mysqli_real_escape_string($conexion, htmlspecialchars($_POST['edad']));

    $contra_usuario = isset($_POST['contra_usuario']) && !empty($_POST['contra_usuario']) ? password_hash($_POST['contra_usuario'], PASSWORD_DEFAULT) : $usuario['contra_usuario'];  

    try {
        $sql_usuario = "UPDATE tbl_usuario SET 
                        usuario_escuela = ?,  
                        nom_usuario = ?, 
                        ape_usuario = ?, 
                        telefono_usuario = ?, 
                        fecha_nacimi_usuario = ?, 
                        contra_usuario = ? 
                      WHERE id_usuario = ?";

        $stmt = mysqli_prepare($conexion, $sql_usuario);

        mysqli_stmt_bind_param($stmt, "ssssssi", $nombre_usuario, $nombre_real, $ape_usuario, $telefono, $fecha_nacimiento, $contra_usuario, $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../menu.php?success=usuario_actualizado");
            exit();
        } else {
            throw new Exception("Error al actualizar los datos del usuario.");
        }

        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        header("Location: ../menu.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
</head>
<body>
<form action="" method="post">
    <label for="nombre_usuario">Nombre de usuario:</label>
    <input type="text" id="nombre_usuario" name="nombre_usuario" value="<?php echo isset($usuario) ? $usuario['usuario_escuela'] : ''; ?>"><br><br>

    <label for="nombre_real">Nombre: </label>
    <input type="text" id="nombre_real" name="nombre_real" value="<?php echo isset($usuario) ? $usuario['nom_usuario'] : ''; ?>"><br><br>
    
    <label for="ape_usuario">Apellidos:</label>
    <input type="text" id="ape_usuario" name="ape_usuario" value="<?php echo isset($usuario) ? $usuario['ape_usuario'] : ''; ?>"><br><br>
    
    <label for="contra_usuario">Contraseña:</label>
    <input type="password" id="contra_usuario" name="contra_usuario" value=""><br><br>
    
    <label for="telefono">Teléfono:</label>
    <input type="tel" id="telefono" name="telefono" value="<?php echo isset($usuario) ? $usuario['telefono_usuario'] : ''; ?>"><br><br>
    
    <label for="edad">Fecha de nacimiento:</label>
    <input type="date" id="edad" name="edad" value="<?php echo isset($usuario) ? $usuario['fecha_nacimi_usuario'] : ''; ?>"><br><br>
    
    <input type="submit" name="btn_editar_usuario" value="Actualizar usuario">
</form>
</body>
</html>