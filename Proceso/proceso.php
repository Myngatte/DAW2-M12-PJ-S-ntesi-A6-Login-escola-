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
            
            // Modificar la consulta para desencriptar la contraseña
            $sql = "SELECT usuario_escuela, AES_DECRYPT(contra_usuario, 'clave_aes') AS contra_desencriptada 
                    FROM tbl_usuario 
                    WHERE usuario_escuela = ?";
            
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, 's', $usuario);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($resultado) == 0) {
                $errores .= ($errores === "") ? "credencialesInvalidas=true" : "&credencialesInvalidas=true";
                header('Location: ../index.php?' . $errores);
                exit();
            } else {
                $fila = mysqli_fetch_assoc($resultado);
                $contra_desencriptada = $fila['contra_desencriptada'];
                
                // Comparar la contraseña ingresada con la desencriptada
                if ($contra_desencriptada !== $contra) {
                    $errores .= ($errores === "") ? "credencialesInvalidas=true" : "&credencialesInvalidas=true";
                    header('Location: ../index.php?' . $errores);
                    exit();
                }
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
