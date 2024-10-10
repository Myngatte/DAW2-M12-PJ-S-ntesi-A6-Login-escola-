<?php

session_start();
// $_SESSION['acceso_index'] = true;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Inicio de Sesión</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <script type="text/Javascript" src="./scriptsjs/validations.js"></script>
    <div class="container">
        <div class="left-section">
            <img src="escudo.png" alt="Escudo" class="logo">
        </div>
        <div class="right-section">
            <h1>Bienvenid@</h1>
            <form action="./Proceso/proceso.php" method="POST">
                <input type="text" name="nombre" id="nombre" placeholder="Usuario">
                <div class="password-container">
                    <input type="password" name="password" id="password" placeholder="Contraseña">
                    <i class="toggle-password fa fa-eye"></i> <!-- Icono para mostrar/ocultar contraseña -->
                </div>
                <button type="submit">Iniciar sesión</button>
            </form>
        </div>
    </div>

    <!-- Script para mostrar/ocultar la contraseña -->
    <script>
        document.querySelector('.toggle-password').addEventListener('click', function () {
            const passwordField = this.previousElementSibling;
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    <?php require_once('conexion.php');?>
</body>
</html>



