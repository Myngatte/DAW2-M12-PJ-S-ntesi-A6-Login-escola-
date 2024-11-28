<?php
session_start();
require_once('./conexion.php');
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ./index.php?error=sesiones');
    exit();
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/menu.css">
    <title>Document</title>
</head>
<body>
    <?php
            include_once('conexion.php');
            try {
            $sql = "SELECT * FROM tbl_usuario";
                $stmt= mysqli_prepare($conexion, $sql);
                mysqli_stmt_execute($stmt);
                $resultado=mysqli_stmt_get_result($stmt);
            echo "<table border='1'>
                <tr>
                    <th>Usuario</th>
                    <th>Nombre completo</th>
                    <th>Telefono</th>
                    <th>Fecha de nacimiento</th>
                    <th>Sexo</th>
                    <th>Acciones</th>
                </tr>";
        
                foreach ($resultado as $fila) {
                     echo "<tr>";
                     echo "<td>" . $fila['usuario_escuela'] . "</td>";
                     echo "<td>" . $fila['nom_usuario'] . " " . $fila['ape_usuario'] . "</td>";
                     echo "<td>" . $fila['telefono_usuario'] . "</td>";
                     echo "<td>" . $fila['fecha_nacimi_usuario'] . "</td>";
                     echo "<td>" . $fila['sexo_usuario'] . "</td>";
                    echo "<td>
                    <a href='editar_usuario.php?id=".$fila['id_usuario']."'>Editar</a>
                    <a href='eliminar_usuario.php?id=".$fila['id_usuario']."'>Eliminar</a>
                    </td>";
                    echo "<tr>";
                }
                echo "</table>";
                mysqli_stmt_close($stmt);
                mysqli_close($conexion);
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        
        
            ?>    
    ?>
</body>
