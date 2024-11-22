<?php
require_once('../conexion.php');
session_start();
if (isset($_POST['boton'])  && !empty($_POST['nombre']) && !empty($_POST['contrasena'])) {
    $contra = isset($_POST['contrasena']) ? mysqli_real_escape_string($conexion, htmlspecialchars($_POST['contrasena'])) : '';
    $usuario = isset($_POST['nombre']) ? mysqli_real_escape_string($conexion, htmlspecialchars($_POST['nombre'])) : '';
    $_SESSION['usuario'] = $usuario;
    try {
        mysqli_autocommit($conexion, false);
        $sql = "SELECT usuario_escuela, contra_usuario,rol_user FROM tbl_usuario WHERE usuario_escuela = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $usuario);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($usuario_db = mysqli_fetch_assoc($resultado)) {
            if (password_verify($contra, $usuario_db['contra_usuario'])) {
                $_SESSION['usuario'] = $usuario;
                header("Location: ../menu.php");    
                exit();
            } else {
                header('Location:../index.php?error=contrasena_incorrecta');
            }
        } else {
            header('Location:../index.php?error=usuario_no_encontrado');
        }

        mysqli_stmt_close($stmt);
        mysqli_commit($conexion);
    } catch (Exception $e) {
        echo "Se produjo un error: " . htmlspecialchars($e->getMessage());
    }
} else {
    header('Location: ../index.php?error=campos_vacios');
}