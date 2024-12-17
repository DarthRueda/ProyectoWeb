<?php
include_once __DIR__ . '/../config/dataBase.php';
include_once __DIR__ . '/../models/menu.php';

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
            $producto = new Menu($row['id'], $row['nombre'], $row['descripcion'], $row['precio'], $row['imagen']);
            $producto->setTipo($row['tipo']);
            array_push($productos, $producto);
        }

        $con->close();
        return $productos;
    }

    //Funciones para filtrar los productos por tipo
    public static function getMenus() {
        return self::getProductsByType('menus');
    }

    public static function getHamburguesas() {
        return self::getProductsByType('hamburguesa');
    }

    public static function getBebidas() {
        return self::getProductsByType('bebida');
    }

    public static function getComplementos() {
        return self::getProductsByType('complemento');
    }

    //Funcion que obtiene los productos por tipo, en este caso, por hamburguesa, bebida, complemento o menu
    private static function getProductsByType($type) {
        $con = DataBase::connect();
        $query = "
            SELECT id_menu AS id, nombre, descripcion, precio, imagen, 'menus' AS tipo FROM menus WHERE 'menus' = '$type'
            UNION
            SELECT id_hamburguesa AS id, nombre, descripcion, precio, imagen, 'hamburguesa' AS tipo FROM hamburguesas WHERE 'hamburguesa' = '$type'
            UNION
            SELECT id_bebida AS id, nombre, descripcion, precio, imagen, 'bebida' AS tipo FROM bebidas WHERE 'bebida' = '$type'
            UNION
            SELECT id_complemento AS id, nombre, descripcion, precio, imagen, 'complemento' AS tipo FROM complementos WHERE 'complemento' = '$type'
        ";

        $result = $con->query($query);
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $producto = new Menu($row['id'], $row['nombre'], $row['descripcion'], $row['precio'], $row['imagen']);
            $producto->setTipo($row['tipo']);
            array_push($productos, $producto);
        }

        $con->close();
        return $productos;
    }

    public static function getMenuById($id) {
        $con = DataBase::connect();
        $query = "SELECT id_menu AS id, nombre, descripcion, precio, imagen, 'menus' AS tipo FROM menus WHERE id_menu = $id";
        $result = $con->query($query);
        $menu = $result->fetch_assoc();
        $con->close();
        return $menu;
    }
}
?>
