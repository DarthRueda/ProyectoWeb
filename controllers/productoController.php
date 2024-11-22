<?php

include_once("models/producto.php");
include_once("models/pedidosDAO.php");

class productoController{
    // Funciones de la clase
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
    public function compra(){
        $view = "views/compra.php";
        include_once 'views/main.php';
    }
    public function añadirCarrito(){
        session_start();
        $producto = [
            'id' => $_POST['id'],
            'nombre' => $_POST['nombre'],
            'descripcion' => $_POST['descripcion'],
            'precio' => $_POST['precio'],
            'imagen' => $_POST['imagen']
        ];
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $_SESSION['cart'][] = $producto;
        header('Location: ?controller=producto&action=carrito');
    }
    public function eliminarCarrito(){
        session_start();
        $id = $_POST['id'];
        foreach ($_SESSION['cart'] as $key => $producto) {
            if ($producto['id'] == $id) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        header('Location: ?controller=producto&action=carrito');
    }
    public function tramitarPedido(){
        session_start();
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            pedidosDAO::guardarPedido($_SESSION['cart']);
            unset($_SESSION['cart']);
        }
        header('Location: ?controller=producto&action=compra');
    }
}
?>