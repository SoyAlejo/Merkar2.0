<?php
require_once __DIR__ .'\..\config\config.inc';

$conn = new mysqli($dbServidor, $dbUsuario, $dbContrasena, $dbBasedatos);
if ($conn->connect_error) {
    die("Conexión errada: ". $conn->connect_error);
}

$conn->set_charset('utf8');
?>