<!-- Archivo usado para probar la conexion de la base de datos -->

<?php
require_once 'config/dataBase.php';

$con = DataBase::connect();

$query = "SELECT nombre FROM hamburguesas";

$result = $con->query($query);


$con->close();
?>