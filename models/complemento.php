<?php

include_once 'models/producto.php';

class Complemento extends Producto{
    
    public function __construct($id, $nombre, $descripcion, $precio, $imagen){
        parent::__construct($id, $nombre, $descripcion, $precio, $imagen);
    }
}
?>