<?php
require_once('../conexion.php');
session_start();
if (isset($_POST['boton'])  && !empty($_POST['nombre']) && !empty($_POST['contrasena'])) {
    $contra = isset($_POST['contrasena']) ? mysqli_real_escape_string($conexion, htmlspecialchars($_POST['contrasena'])) : '';
    $usuario = isset($_POST['nombre']) ? mysqli_real_escape_string($conexion, htmlspecialchars($_POST['nombre'])) : '';
    $_SESSION['usuario'] = $usuario;
    try {
        mysqli_autocommit($conexion, false);
        $sql = "SELECT 
        u.id_usuario, 
        u.usuario_escuela, 
        u.contra_usuario, 
        r.nombre_rol
        FROM 
            tbl_usuario u
        INNER JOIN 
            tbl_rol r
        ON 
            u.rol_user = r.id_rol
        WHERE u.usuario_escuela = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $usuario);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($usuario_db = mysqli_fetch_assoc($resultado)) {
            $_SESSION['id_usuario'] = $usuario_db['id_usuario'];
            if ($usuario_db['nombre_rol'] !== "admin") {
                header('Location:../index.php?error=rol_invalido');
                exit();
            }else{
            if (password_verify($contra, $usuario_db['contra_usuario'])) {
                header("Location: ../menu.php");    
                exit();
            } else {
                header('Location:../index.php?error=usr_mal');
            }
            }
        } else {
            header('Location:../index.php?error=usr_mal');
        }

        mysqli_stmt_close($stmt);
        mysqli_commit($conexion);
    } catch (Exception $e) {
        echo "Se produjo un error: " . htmlspecialchars($e->getMessage());
    }
} else {
    header('Location: ../index.php?error=campos_vacios');
}