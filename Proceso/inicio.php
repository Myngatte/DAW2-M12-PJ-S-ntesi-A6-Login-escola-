<?php
session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['contra'])) {
    header('Location: ../index.php?error=sesiones');
    exit();
} else {
    echo "Bienvenido " . $_SESSION['usuario'];
}
