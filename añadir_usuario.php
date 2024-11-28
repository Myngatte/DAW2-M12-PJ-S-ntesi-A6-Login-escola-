<?php
session_start();
require_once('./conexion.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ./index.php?error=sesiones');
    exit();
}

// Obtener el id_usuario desde la sesión
$id_usuario = $_SESSION['id_usuario'];

// Inicializar la variable de mensaje
$message = "";

// Realizar una consulta para obtener el nombre del usuario
$sql = "SELECT nom_usuario FROM tbl_usuario WHERE id_usuario = ?";
$stmt = mysqli_prepare($conexion, $sql);

if ($stmt) {
    // Vincular el parámetro y ejecutar la consulta
    mysqli_stmt_bind_param($stmt, "i", $id_usuario); // 'i' es para un tipo de dato entero (id_usuario)
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nom_usuario); // Obtener el resultado (nombre del usuario)

    // Si la consulta devuelve un resultado, obtener el nombre
    if (mysqli_stmt_fetch($stmt)) {
        // Si todo es correcto, se tiene el nombre del usuario
    } else {
        // Si no se encuentra el usuario, asignar un valor predeterminado
        $nom_usuario = "Usuario";
    }

    mysqli_stmt_close($stmt);
} else {
    // En caso de error en la consulta
    $nom_usuario = "Usuario";
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_escuela = htmlspecialchars($_POST['usuario_escuela'], ENT_QUOTES, 'UTF-8');
    $nom_usuario_input = htmlspecialchars($_POST['nom_usuario'], ENT_QUOTES, 'UTF-8');
    $ape_usuario = htmlspecialchars($_POST['ape_usuario'], ENT_QUOTES, 'UTF-8');
    $telefono_usuario = htmlspecialchars($_POST['telefono_usuario'], ENT_QUOTES, 'UTF-8');
    $fecha_nacimi_usuario = htmlspecialchars($_POST['fecha_nacimi_usuario'], ENT_QUOTES, 'UTF-8');
    $sexo_usuario = htmlspecialchars($_POST['sexo_usuario'], ENT_QUOTES, 'UTF-8');
    $rol_usuario = 2;
    $contra_usuario = password_hash('Colegio123.', PASSWORD_BCRYPT);

    // Verificar si el valor de sexo_usuario es válido (M o F)
    if ($sexo_usuario !== "" && !in_array($sexo_usuario, ['M', 'F'])) {
        $message = "El valor del sexo no es válido.";
    } else {
        // Verificar si el nombre de usuario ya existe
        $sql_check_user = "SELECT COUNT(*) FROM tbl_usuario WHERE usuario_escuela = ?";
        $stmt_check_user = mysqli_prepare($conexion, $sql_check_user);

        if ($stmt_check_user) {
            mysqli_stmt_bind_param($stmt_check_user, "s", $usuario_escuela);
            mysqli_stmt_execute($stmt_check_user);
            mysqli_stmt_bind_result($stmt_check_user, $user_count);
            mysqli_stmt_fetch($stmt_check_user);
            mysqli_stmt_close($stmt_check_user);

            if ($user_count > 0) {
                $message = "El nombre de usuario ya está en uso. Por favor, elige otro.";
            } else {
                // Si todos los campos son válidos y el usuario no existe
                if ($usuario_escuela && $nom_usuario_input && $ape_usuario && $contra_usuario && $telefono_usuario && $fecha_nacimi_usuario && $sexo_usuario) {
                    $sql = "INSERT INTO tbl_usuario (usuario_escuela, nom_usuario, ape_usuario, contra_usuario, telefono_usuario, fecha_nacimi_usuario, sexo_usuario, rol_user) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($conexion, $sql);

                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "sssssssi", $usuario_escuela, $nom_usuario_input, $ape_usuario, $contra_usuario, $telefono_usuario, $fecha_nacimi_usuario, $sexo_usuario, $rol_usuario);
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
        } else {
            $message = "Error al verificar el nombre de usuario: " . mysqli_error($conexion);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Nuevo Estudiante</title>
    <link rel="stylesheet" href="./css/añadir_usuario.css">
</head>
<body>
    <div class="sidebar">
        <img src="./images/profile.png" alt="Admin">
        <h3><?php echo htmlspecialchars($nom_usuario); ?></h3>
        <span>Admin</span>
        <a href="./menu.php">Estudiantes</a>
        <a href="./menu.php?notas=true">Notas</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
    <div class="main">
        <h1>Añadir Nuevo Estudiante</h1>
        <?php if ($message) echo "<p class='message'>$message</p>"; ?>
        <form action="añadir_usuario.php" method="post" class="form">
            <!-- Columna izquierda -->
            <div class="form-column">
                <div class="form-group">
                    <label for="usuario_escuela">Usuario</label>
                    <input type="text" id="usuario_escuela" name="usuario_escuela" placeholder="Introduce el usuario...">
                </div>
                <br>
                <div class="form-group">
                    <label for="nom_usuario">Nombre</label>
                    <input type="text" id="nom_usuario" name="nom_usuario" placeholder="Introduce el nombre...">
                </div>
                <br>
                <div class="form-group">
                    <label for="ape_usuario">Apellidos</label>
                    <input type="text" id="ape_usuario" name="ape_usuario" placeholder="Introduce los apellidos...">
                </div>
            </div>
            <!-- Columna derecha -->
            <div class="form-column">
                <div class="form-group">
                    <label for="telefono_usuario">Teléfono</label>
                    <input type="text" id="telefono_usuario" name="telefono_usuario" placeholder="Introduce el teléfono...">
                </div>
                <br>
                <div class="form-group">
                    <label for="fecha_nacimi_usuario">Fecha de Nacimiento</label>
                    <input type="date" id="fecha_nacimi_usuario" name="fecha_nacimi_usuario">
                </div>
                <br>
                <div class="form-group">
                    <label for="sexo_usuario">Sexo</label>
                    <select id="sexo_usuario" name="sexo_usuario">
                        <option value="">Selecciona el sexo</option>
                        <option value="M">Hombre</option>
                        <option value="F">Mujer</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-primary">Siguiente</button>
        </form>
    </div>
</body>
</html>
