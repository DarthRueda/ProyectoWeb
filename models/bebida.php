<?php

include_once 'model/producto.php';

class Bebida extends Producto{
    
    public function __construct($id, $nombre, $descripcion, $precio){
        
        $this->id_bebida = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
    }
}
?>