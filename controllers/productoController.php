<?php

include_once("models/producto.php");

class productoController{
    public function index(){
        $view = "views/home.php";
        include_once 'views/main.php';
    }
    public function carta(){
        $view = "views/carta.php";
        include_once 'views/main.php';
    }
    public function carrito(){
        $view = "views/carrito.php";
        include_once 'views/main.php';
    }
}
?>