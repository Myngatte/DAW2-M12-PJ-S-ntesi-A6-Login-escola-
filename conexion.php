<?php
$host = '127.0.0.1';
$bdname = 'bd_escuela';
$usuario = 'root';
$contrasenya = '1234';
try{
    $conexion = new PDO("mysql:host=$host; dbname=$bdname" , $usuario, $contrasenya);
}catch(PDOException $e){
    echo "El error es:".$e->getMessage();
}
