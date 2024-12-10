<?php

include_once 'models/producto.php';

class Menu extends Producto{
    
    public function __construct($id, $nombre, $descripcion, $precio, $imagen){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->imagen = $imagen;
    }
}
?>