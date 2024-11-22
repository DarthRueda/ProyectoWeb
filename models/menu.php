<?php

include_once 'models/producto.php';

class Menu extends Producto{
    
    public function __construct($id, $nombre, $descripcion, $precio, $imagen){
        parent::__construct($id, $nombre, $descripcion, $precio, $imagen);
    }
}
?>