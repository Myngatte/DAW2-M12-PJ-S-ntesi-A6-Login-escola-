<?php
    session_start();
    session_abort();
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina principal</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script type="text/Javascript" src="./scriptsjs/validations.js"></script>
    <title>Pagina principal</title>  
</head>
<body>
    <div class="container">
        <div class="left-section">
            <img src="./Imagenes/escudo.png" alt="Escudo" class="logo">
        </div>
        <div class="right-section">
            <h1>Bienvenid@</h1>
            <form action="./Proceso/proceso.php" onsubmit="return validarFormulario()" method="POST">
                <input type="text" id="nombre" name="nombre" placeholder="Usuario">
                <p id="error-nombre" style="color: red;"></p>
                <br>

                <div class="contrasena-container">
                    <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña">
                    <i class="toggle-contrasena fa fa-eye"></i>
                </div>

                <p id="error-contrasena" style="color: red;"></p>
                <br>
                <button type="submit" name="boton" value="boton">Iniciar sesión</button>
            </form>
        </div>
    </div>
    <!-- Script para mostrar/ocultar la contraseña -->
    <script>
        document.querySelector('.toggle-contrasena').addEventListener('click', function () {
            const contrasenaField = this.previousElementSibling;
            const type = contrasenaField.getAttribute('type') === 'password' ? 'text' : 'password';
            contrasenaField.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>