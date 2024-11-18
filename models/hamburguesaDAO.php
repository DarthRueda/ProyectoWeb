<?php
include_once 'config/dataBase.php';
include_once 'models/hamburguesa.php';

class hamburguesaDAO{
    public static function getAll(){
        $con = DataBase::connect();

        $query = "SELECT id_hamburguesa AS id, nombre, descripcion, precio FROM hamburguesas";

        $result = $con->query($query);

        $hamburguesas = [];

        while($row = $result -> fetch_assoc()){
            $hamburguesa = new Hamburguesa($row['id'], $row['nombre'], $row['descripcion'], $row['precio']);
            array_push($hamburguesas, $hamburguesa);
        }

        $con -> close();
        return $hamburguesas;
    }
}
?>