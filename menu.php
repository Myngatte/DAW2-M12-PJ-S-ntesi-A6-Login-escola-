<?php
session_start();
require_once('./conexion.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ./index.php?error=sesiones');
    exit();
}

// Mensaje de acciones
$message = "";

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
    <title>Lista de Estudiantes</title>
    <link rel="stylesheet" href="./css/menu.css">
</head>
<body>
    <!-- Barra lateral -->
    <div class="sidebar">
        <img src="./img/avatar.jpg" alt="Foto de perfil">
        <h3>Mario Bros</h3>
        <span>Admin</span>
        <a href="#" class="active">Estudiantes</a>
        <a href="#notas">Notas</a>
        <div class="logout" onclick="window.location.href='./logout.php';">Logout</div>
    </div>

    <!-- Contenido principal -->
    <div class="container" style="margin-left: 260px;"> <!-- Ajusta el margen para la barra lateral -->
        <header>
            <h1>Lista de Estudiantes</h1>
            <div>
                <input type="search" placeholder="Buscar...">
                <input type="date">
                <button>FILTRAR</button>
                <button onclick="window.location.href='./añadir_usuario.php';">AÑADIR ESTUDIANTE</button>
            </div>
        </header>

        <!-- Mensaje de acciones -->
        <?php if ($message): ?>
            <p style="color: green;"><?= htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <!-- Tabla de estudiantes -->
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Nombre Usuario</th>
                    <th>Fecha Nacimiento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Obtener la lista de estudiantes
                $sql = "SELECT id_usuario, nom_usuario, ape_usuario, usuario_escuela, telefono_usuario, fecha_nacimi_usuario FROM tbl_usuario";
                $result = mysqli_query($conexion, $sql);

                if ($result && mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)):
                ?>
                <tr>
                    <td>
                        <img src="./img/default-profile.png" alt="Foto" class="profile-pic">
                        <?= htmlspecialchars($row['nom_usuario'] . " " . $row['ape_usuario']); ?>
                    </td>
                    <td><?= htmlspecialchars($row['usuario_escuela']); ?></td>
                    <td><?= htmlspecialchars($row['telefono_usuario']); ?></td>
                    <td><?= htmlspecialchars($row['usuario_escuela']); ?></td>
                    <td><?= htmlspecialchars($row['fecha_nacimi_usuario']); ?></td>
                    <td>
                        <a href="./editar_usuario.php?id=<?= urlencode($row['id_usuario']); ?>">✏️</a>
                        <form action="" method="POST" style="display: inline;">
                            <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($row['id_usuario']); ?>">
                            <button type="submit" name="delete_user" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">🗑️</button>
                        </form>
                    </td>
                </tr>
                <?php
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="6">No se encontraron estudiantes.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="pagination">
            <button>1</button>
            <button class="active">2</button>
            <button>3</button>
            <button>...</button>
            <button>68</button>
        </div>
    </div>
</body>
</html>
