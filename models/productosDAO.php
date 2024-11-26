<?php
include_once 'config/dataBase.php';
include_once 'models/menu.php';

class productosDAO {
    public static function getAll() {
        $con = DataBase::connect();
        // Seleccionamos todos los productos de la base de datos
        $query = "
            SELECT id_menu AS id, nombre, descripcion, precio, imagen, 'menus' AS tipo FROM menus
            UNION
            SELECT id_hamburguesa AS id, nombre, descripcion, precio, imagen, 'hamburguesa' AS tipo FROM hamburguesas
            UNION
            SELECT id_bebida AS id, nombre, descripcion, precio, imagen, 'bebida' AS tipo FROM bebidas
            UNION
            SELECT id_complemento AS id, nombre, descripcion, precio, imagen, 'complemento' AS tipo FROM complementos
        ";

        $result = $con->query($query);

        $productos = []; // Array donde guardaremos los productos

        // Bucle para recorrer los resultados
        while ($row = $result->fetch_assoc()) {
            $producto = [
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'descripcion' => $row['descripcion'],
                'precio' => $row['precio'],
                'imagen' => $row['imagen'],
                'tipo' => $row['tipo']
            ];
            array_push($productos, $producto);
        }

        $con->close();
        return $productos;
    }
}
?>
