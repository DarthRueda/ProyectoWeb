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

    public function exito() {
        if (isset($_POST['id_pedido'])) {
            pedidosDAO::marcarPedidoComoPagado($_POST['id_pedido']);
        }
        $view = "views/exito.php";
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

        // Devolver respuesta en JSON para mostrarr que el producto se ha añadido alcarrito
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'product' => [
                'nombre' => $_POST['nombre'],
                'imagen' => $_POST['imagen']
            ]
        ]);
        exit;
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
            $codigo_promocional = isset($_POST['codigo_promocional']) ? $_POST['codigo_promocional'] : null;
            $_SESSION['id_pedido'] = pedidosDAO::guardarPedido($_SESSION['cart'], $codigo_promocional);
            unset($_SESSION['cart']);
        }
        header('Location: ?controller=producto&action=compra');
    }

}
?>