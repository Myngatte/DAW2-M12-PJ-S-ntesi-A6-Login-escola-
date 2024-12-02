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
                // Verificar si se subió una imagen
                $foto_usuario = null;
                if (isset($_FILES['foto_usuario']) && $_FILES['foto_usuario']['error'] == 0) {
                    // Verificar que el archivo es una imagen PNG
                    $file_tmp = $_FILES['foto_usuario']['tmp_name'];
                    $file_name = $_FILES['foto_usuario']['name'];
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                    if ($file_ext == 'png') {
                        // Generar un nombre único para la imagen
                        $foto_usuario = $usuario_escuela . '.png';
                        $destination = './img/' . $foto_usuario;

                        // Mover el archivo a la carpeta img
                        if (move_uploaded_file($file_tmp, $destination)) {
                            $message = "Imagen cargada correctamente.";
                        } else {
                            $message = "Error al mover la imagen.";
                        }
                    } else {
                        $message = "Solo se permiten archivos PNG.";
                    }
                }

                // Si todos los campos son válidos y el usuario no existe
                if ($usuario_escuela && $nom_usuario_input && $ape_usuario && $contra_usuario && $telefono_usuario && $fecha_nacimi_usuario && $sexo_usuario) {
                    $sql = "INSERT INTO tbl_usuario (usuario_escuela, nom_usuario, ape_usuario, contra_usuario, telefono_usuario, fecha_nacimi_usuario, sexo_usuario, rol_user, foto_usuario) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmt = mysqli_prepare($conexion, $sql);
                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "sssssssis", $usuario_escuela, $nom_usuario_input, $ape_usuario, $contra_usuario, $telefono_usuario, $fecha_nacimi_usuario, $sexo_usuario, $rol_usuario, $foto_usuario);

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
// Realizar una consulta para obtener el nombre del usuario y la foto
$sql = "SELECT nom_usuario, foto_usuario FROM tbl_usuario WHERE id_usuario = ?";
$stmt = mysqli_prepare($conexion, $sql);

if ($stmt) {
    // Vincular el parámetro y ejecutar la consulta
    mysqli_stmt_bind_param($stmt, "i", $id_usuario); // 'i' es para un tipo de dato entero (id_usuario)
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nom_usuario, $foto_usuario); // Obtener el resultado (nombre del usuario y la foto)

    // Si la consulta devuelve un resultado, obtener el nombre y la foto
    if (mysqli_stmt_fetch($stmt)) {
        // Si todo es correcto, se tiene el nombre y la foto del usuario
    } else {
        // Si no se encuentra el usuario, asignar un valor predeterminado
        $nom_usuario = "Usuario";
        $foto_usuario = "default.png"; // Foto predeterminada en caso de no tener una imagen
    }

    mysqli_stmt_close($stmt);
} else {
    // En caso de error en la consulta
    $nom_usuario = "Usuario";
    $foto_usuario = "default.png"; // Foto predeterminada
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
<button class="hamburger-btn">☰</button>
    <div class="sidebar" id="sidebar">
        <img src="./img/<?php echo htmlspecialchars($foto_usuario); ?>" alt="<?php echo htmlspecialchars($nom_usuario); ?>" class="img-uniform">
        <h3><?php echo htmlspecialchars($nom_usuario); ?></h3>
        <span>Admin</span>
        <br><br><br><br><br>
        <a href="./menu.php">Estudiantes</a>
        <a href="./menu.php?notas=true">Notas</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <div class="main">
        <h1>Añadir Nuevo Estudiante</h1>
        <?php if ($message) echo "<p class='message'>$message</p>"; ?>
        <form action="añadir_usuario.php" method="post" class="form" enctype="multipart/form-data">
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
                <br>
                <!-- Campo para subir la foto -->
                <div class="form-group">
                    <label for="foto_usuario">Foto de perfil (PNG)</label>
                    <input type="file" id="foto_usuario" name="foto_usuario" accept="image/png">
                </div>
            </div>
            <button type="submit" class="btn-primary">Siguiente</button>
        </form>
        <script>
    // Seleccionar los elementos
    const sidebar = document.getElementById('sidebar');
    const hamburgerBtn = document.querySelector('.hamburger-btn');

    // Agregar un evento de clic al botón hamburguesa
    hamburgerBtn.addEventListener('click', () => {
        // Alternar la clase "show" en el sidebar
        sidebar.classList.toggle('show');

        // Verificar si la clase "show" está presente en el sidebar
        if (sidebar.classList.contains('show')) {
            // Si la clase "show" está activa, quitar el color del botón
            hamburgerBtn.style.background = "none";
        } else {
            // Si la clase "show" no está activa, restaurar el color original
            hamburgerBtn.style.background = "";  // Esto restaura el color original
        }
    });
</script>
    <!-- AÑADIMOS EL VALIDATE_NEW_STUDENT.JS -->
        <script src="./scriptsjs/validate_new_student.js"></script>

    </div>
</body>
</html>
