<?php

include_once 'models/producto.php';

class Hamburguesa extends Producto{
    
    public function __construct($id, $nombre, $descripcion, $precio){
        parent::__construct($id, $nombre, $descripcion, $precio);
    }
}
?>