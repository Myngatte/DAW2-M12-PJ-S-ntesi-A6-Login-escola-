<?php
    session_start();
    if (filter_has_var(INPUT_GET, 'boton')) {
        if (!isset($_GET['nombre']) || !isset($_GET['contrasena']) || $_GET['nombre'] === "" || $_GET['contrasena'] === "") {
            header('Location: ../index.php?error=campovacio');
            exit(); 
        } else {
            $_SESSION['usuario'] = $_GET['nombre'];  
            $_SESSION['contra'] = $_GET['contrasena']; 
            header('Location: inicio.php');
            exit();
        }
    } else {
        header('Location: ../index.php?error=boton');
        exit();
    }
?>

