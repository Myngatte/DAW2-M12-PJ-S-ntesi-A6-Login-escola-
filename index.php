<?php

session_start();
// $_SESSION['acceso_index'] = true;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina principal</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">  
    <title>Pagina principal</title>  
</head>
<body>
    <script src="./scriptsjs/validations.js"></script>
    <div id="todo">
        <div id="contenedor_derecha">
            <img id="derecha" src="./img/header.png" alt="">
        </div>        
        <div id="contenedor_izquierda"> 
            <img id="izquierda" src="./img/logo.png" alt="">
                <div id="cuadrado">
                        <h3 class="gestion"> Login </h3>
                        <form method="GET" action="./Proceso/proceso.php">
                            <label for="nombre">Nombre de usuario:</label><br>
                            <input type="text" id="nombre" name="nombre"><br><br>
                            
                            <label for="contrasena">Contraseña:</label><br>
                            <input type="password" id="contrasena" name="contrasena"><br><br>
                            
                        <button type="submit" name="boton" id="boton" value="1">Iniciar sesion</button>
                        </form>
                </div>
        </div>   
        <?php require_once('conexion.php');?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>



