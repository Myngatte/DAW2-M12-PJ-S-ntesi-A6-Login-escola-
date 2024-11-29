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

// Realizar una consulta para obtener el nombre y la foto del usuario
$sql = "SELECT nom_usuario, foto_usuario FROM tbl_usuario WHERE id_usuario = ?";
$stmt = mysqli_prepare($conexion, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id_usuario); 
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nom_usuario, $foto_usuario);

    if (!mysqli_stmt_fetch($stmt)) {
        $nom_usuario = "Usuario";
        $foto_usuario = "default.png"; // Imagen por defecto si no hay foto
    }
    mysqli_stmt_close($stmt);
} else {
    $nom_usuario = "Usuario";
    $foto_usuario = "default.png"; // Imagen por defecto
}

// Obtener los datos para los filtros
$sexos = ['M' => 'Masculino', 'F' => 'Femenino'];
$sexo_filter = isset($_GET['sexo']) ? $_GET['sexo'] : '';
$nombre_filter = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$apellido_filter = isset($_GET['apellido']) ? $_GET['apellido'] : '';
$usuario_filter = isset($_GET['usuario']) ? $_GET['usuario'] : '';

// Consulta de usuarios con filtros acumulativos
$sql = "SELECT * FROM tbl_usuario WHERE 1=1 AND rol_user = 2";

// Aplicar filtros según las selecciones
if ($sexo_filter) {
    $sql .= " AND sexo_usuario = ? ";
}
if ($nombre_filter) {
    $sql .= " AND nom_usuario LIKE ?";
}
if ($apellido_filter) {
    $sql .= " AND ape_usuario LIKE ?";
}
if ($usuario_filter) {
    $sql .= " AND usuario_escuela LIKE ?";
}

// Preparar la consulta
$stmt = mysqli_prepare($conexion, $sql);

// Definir variables para los parámetros
$nombre_param = "%" . $nombre_filter . "%";
$apellido_param = "%" . $apellido_filter . "%";
$usuario_param = "%" . $usuario_filter . "%";

// Vincular los parámetros para los filtros
if ($sexo_filter && $nombre_filter && $apellido_filter && $usuario_filter) {
    mysqli_stmt_bind_param($stmt, "ssss", $sexo_filter, $nombre_param, $apellido_param, $usuario_param);
} elseif ($sexo_filter && $nombre_filter && $apellido_filter) {
    mysqli_stmt_bind_param($stmt, "sss", $sexo_filter, $nombre_param, $apellido_param);
} elseif ($sexo_filter && $nombre_filter && $usuario_filter) {
    mysqli_stmt_bind_param($stmt, "sss", $sexo_filter, $nombre_param, $usuario_param);
} elseif ($sexo_filter && $apellido_filter && $usuario_filter) {
    mysqli_stmt_bind_param($stmt, "sss", $sexo_filter, $apellido_param, $usuario_param);
} elseif ($nombre_filter && $apellido_filter && $usuario_filter) {
    mysqli_stmt_bind_param($stmt, "sss", $nombre_param, $apellido_param, $usuario_param);
} elseif ($sexo_filter && $nombre_filter) {
    mysqli_stmt_bind_param($stmt, "ss", $sexo_filter, $nombre_param);
} elseif ($sexo_filter && $apellido_filter) {
    mysqli_stmt_bind_param($stmt, "ss", $sexo_filter, $apellido_param);
} elseif ($sexo_filter && $usuario_filter) {
    mysqli_stmt_bind_param($stmt, "ss", $sexo_filter, $usuario_param);
} elseif ($nombre_filter && $apellido_filter) {
    mysqli_stmt_bind_param($stmt, "ss", $nombre_param, $apellido_param);
} elseif ($nombre_filter && $usuario_filter) {
    mysqli_stmt_bind_param($stmt, "ss", $nombre_param, $usuario_param);
} elseif ($apellido_filter && $usuario_filter) {
    mysqli_stmt_bind_param($stmt, "ss", $apellido_param, $usuario_param);
} elseif ($sexo_filter) {
    mysqli_stmt_bind_param($stmt, "s", $sexo_filter);
} elseif ($nombre_filter) {
    mysqli_stmt_bind_param($stmt, "s", $nombre_param);
} elseif ($apellido_filter) {
    mysqli_stmt_bind_param($stmt, "s", $apellido_param);
} elseif ($usuario_filter) {
    mysqli_stmt_bind_param($stmt, "s", $usuario_param);
}

// Ejecutar la consulta
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Estudiantes</title>
    <link rel="stylesheet" href="./css/menu.css">
</head>
<body>
    <div class="sidebar">
        <img src="./img/<?php echo htmlspecialchars($foto_usuario); ?>" alt="<?php echo htmlspecialchars($nom_usuario); ?>" class="img-uniform">
        <h3><?php echo htmlspecialchars($nom_usuario); ?></h3>
        <span>Admin</span>
        <br><br><br><br><br>
        <a href="./menu.php">Estudiantes</a>
        <a href="./notas.php">Notas</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <!-- Contenido principal -->
    <div class="container" style="margin-left: 260px;">
        <header>
            <h1>Lista de Estudiantes</h1>
            <a href="./añadir_usuario.php"><button class="button" type="submit">Nuevo usuario</button></a>
        </header>

        <!-- Filtros -->
        <form method="get" action="menu.php">
            <div class="filters">
                <label for="sexo">Sexo:</label>
                <select name="sexo" id="sexo">
                    <option value="">Todos</option>
                    <?php foreach ($sexos as $key => $value): ?>
                        <option value="<?php echo $key; ?>" <?php echo $sexo_filter == $key ? 'selected' : ''; ?>><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" value="<?php echo $nombre_filter; ?>">

                <label for="apellido">Apellido:</label>
                <input type="text" name="apellido" id="apellido" value="<?php echo $apellido_filter; ?>">

                <label for="usuario">Nombre de Usuario:</label>
                <input type="text" name="usuario" id="usuario" value="<?php echo $usuario_filter; ?>">

                <button type="submit">Filtrar</button>
                <!-- Botón para eliminar los filtros -->
                <form method="get" action="menu.php" style="margin-top: 10px;">
                    <button type="submit" name="clear_filters" value="true">Eliminar filtros</button>
                </form>
            </div>
        </form>

        <?php
        // Verificar si el botón de eliminar filtros ha sido presionado
        if (isset($_GET['clear_filters'])) {
            // Recargar la página sin filtros
            header("Location: menu.php");
            exit();
        }
        ?>

        <!-- Mensajes -->
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Tabla de usuarios -->
        <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Imagen</th> <!-- Nueva columna para la imagen -->
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Nombre Usuario</th>
                        <th>Fecha Nacimiento</th>
                        <th>Sexo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <!-- Mostrar la imagen de perfil del usuario -->
                            <td>
                                <a href="usuario.php?id=<?php echo urlencode($fila['id_usuario']); ?>">
                                    <img src="./img/<?php echo htmlspecialchars($fila['foto_usuario'] ?? 'default.png'); ?>" alt="Foto de <?php echo htmlspecialchars($fila['nom_usuario']); ?>" class="img-uniform">
                                </a>
                            </td>

                            <td><?php echo htmlspecialchars($fila['nom_usuario']); ?> <?php echo htmlspecialchars($fila['ape_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($fila['telefono_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($fila['usuario_escuela']); ?></td>
                            <td><?php echo htmlspecialchars($fila['fecha_nacimi_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($fila['sexo_usuario']); ?></td>
                            <td>
                                <a href='editar_usuario.php?id=<?php echo urlencode($fila['id_usuario']); ?>'>Editar</a>
                                <a href='eliminar_usuario.php?id=<?php echo urlencode($fila['id_usuario']); ?>'>Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No se encontraron usuarios con los filtros seleccionados.</p>
        <?php endif; ?>
    </div>
</body>
</html>
