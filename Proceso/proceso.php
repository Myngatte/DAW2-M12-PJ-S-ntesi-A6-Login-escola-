<?php
if(!filter_has_var(INPUT_POST, 'enviar')){
    header('Location: ../index.php');
    exit();
} else if (isset($_POST['usuario']) && isset($_POST['contra'])) {
    session_start();
    $usuario = $_POST['usuario'];
    $contra = $_POST['contra'];
    echo $usuario;
}
?>