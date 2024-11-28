<?php
session_start();
require_once('./conexion.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ./index.php?error=sesiones');
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_escuela = htmlspecialchars($_POST['usuario_escuela'], ENT_QUOTES, 'UTF-8');
    $nom_usuario = htmlspecialchars($_POST['nom_usuario'], ENT_QUOTES, 'UTF-8');
    $ape_usuario = htmlspecialchars($_POST['ape_usuario'], ENT_QUOTES, 'UTF-8');
    $telefono_usuario = htmlspecialchars($_POST['telefono_usuario'], ENT_QUOTES, 'UTF-8');
    $fecha_nacimi_usuario = htmlspecialchars($_POST['fecha_nacimi_usuario'], ENT_QUOTES, 'UTF-8');
    $sexo_usuario = htmlspecialchars($_POST['sexo_usuario'], ENT_QUOTES, 'UTF-8');

    if ($usuario_escuela && $nom_usuario && $ape_usuario && $telefono_usuario && $fecha_nacimi_usuario && $sexo_usuario) {
        $sql = "INSERT INTO tbl_usuario (usuario_escuela, nom_usuario, ape_usuario, telefono_usuario, fecha_nacimi_usuario, sexo_usuario) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssss", $usuario_escuela, $nom_usuario, $ape_usuario, $telefono_usuario, $fecha_nacimi_usuario, $sexo_usuario);
            if (mysqli_stmt_execute($stmt)) {
                $message = "Usuario añadido correctamente.";
            } else {
                $message = "Error al añadir el usuario: " . mysqli_error($conexion);
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        $message = "Por favor, completa todos los campos.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Usuario</title>
    <link rel="stylesheet" href="./css/añadir_usuario.css">
    <script src="./scriptsjs/validate_new_student.js"></script>
</head>
<body>
    <div class="container">
        <h1>Añadir Usuario</h1>
        <?php if ($message) echo "<p>$message</p>"; ?>
        <form action="añadir_usuario.php" method="post">
            <input type="text" name="usuario_escuela" placeholder="Usuario Escuela" required>
            <input type="text" name="nom_usuario" placeholder="Nombre" required>
            <input type="text" name="ape_usuario" placeholder="Apellido" required>
            <input type="text" name="telefono_usuario" placeholder="Teléfono" required>
            <input type="date" name="fecha_nacimi_usuario" placeholder="Fecha de Nacimiento" required>
            <select name="sexo_usuario" required>
                <option value="">Seleccionar Sexo</option>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
            </select>
            <button type="submit" class="btn btn-primary">Añadir</button>
        </form>
        <a href="menu.php" class="btn">Volver</a>
    </div>
</body>
</html>
