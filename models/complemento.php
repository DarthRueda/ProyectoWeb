<?php

include_once 'model/producto.php';

class Comlemento extends Producto{
    
    public function __construct($id, $nombre, $descripcion, $precio){
        
        $this->id_complemento = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
    }
}
?>