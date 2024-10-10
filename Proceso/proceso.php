<?php
session_start();
if (filter_has_var(INPUT_POST, 'boton')) {
    $errores = ""; 
    if (!isset($_POST['nombre']) || !isset($_POST['contrasena']) || $_POST['nombre'] === "" || $_POST['contrasena'] === "") {
        header('Location: ../index.php?campovacio=true');
    } else {
        $_SESSION['usuario'] = $_POST['nombre'];  
        $_SESSION['contra'] = $_POST['contrasena'];
        $validarContra = '/^(?=.*[a-z])(?=.*\d).{8,}$/i';

        if (!preg_match('/^[a-zA-Z]+$/', $_SESSION['usuario'])) {
            $errores .= ($errores === "") ? "usuarioInvalido=true" : "&usuarioInvalido=true";
        }
        if (!preg_match($validarContra, $_SESSION['contra'])) {
            $errores .= ($errores === "") ? "contrasenaInvalida=true" : "&contrasenaInvalida=true";
        }
        if ($errores !== "") {
            session_abort();
            header('Location: ../index.php?' . $errores);
            exit();
        }
        require_once '../conexion.php';
        try {
            // Evitar inyección de código
            $usuario = mysqli_real_escape_string($conn, $_SESSION['usuario']);
            $contra = mysqli_real_escape_string($conn, $_SESSION['contra']);
            $sql = "SELECT usuario_escuela, contra_usuario FROM tbl_usuario WHERE usuario_escuela = ? AND contra_usuario = ?";
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, 'ss', $usuario, $contra);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) == 0) {
                $errores .= ($errores === "") ? "credencialesInvalidas=true" : "&credencialesInvalidas=true";
                header('Location: ../index.php?' . $errores);
                exit();
            }
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header('Location: inicio.php');
        } catch (Exception $e) {
            echo "Error al agregar el registro: " . $e->getMessage();
            die();
        }
    }
} else {
    session_abort();
    header('Location: ../index.php?error=boton');
    exit();
}
?>

