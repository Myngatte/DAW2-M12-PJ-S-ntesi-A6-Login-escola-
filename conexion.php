<?php

$dbserver="localhost";
$dbusername="root";
$dbpassword="";
$dbbasedatos="bd_escuela";
try {
    $conexion = mysqli_connect($dbserver, $dbusername,$dbpassword, $dbbasedatos);
}catch (Exception $e) {
    echo "Error de conexión: ". $e->getMessage();
    die();
}