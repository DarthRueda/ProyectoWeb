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
            'imagen' => $_POST['imagen'],
            'tipo' => $_POST['tipo'],
            'cantidad' => 1
        ];

        if ($producto['tipo'] == 'menus') {
            $producto['bebida'] = $_POST['bebidaId'];
            $producto['complemento'] = $_POST['complementoId'];
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Comprobar si el producto ya está en el carrito
        $existe = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $producto['id'] && $item['tipo'] == $producto['tipo']) {
                $item['cantidad']++;
                $existe = true;
                break;
            }
        }

        if (!$existe) {
            $_SESSION['cart'][] = $producto;
        }

        // Devolver respuesta en JSON para mostrar que el producto se ha añadido al carrito
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
        $tipo = $_POST['tipo']; // Comprobamos el tipo de producto (menu, hamburguesa, bebida, complemento)
        foreach ($_SESSION['cart'] as $key => $producto) {
            if ($producto['id'] == $id && $producto['tipo'] == $tipo) { // Comprobamos si el producto es el que queremos eliminar mediante su id y tipo
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

    public function actualizarCantidad() { //Funcion para actualizar la cantidad de productos en el carrito
        session_start();
        $id = $_POST['id'];
        $tipo = $_POST['tipo']; //Tipo de producto (menu, hamburguesa, bebida, complemento)
        $action = $_POST['action']; //Accion a realizar (increase, decrease)

        foreach ($_SESSION['cart'] as &$producto) {
            if ($producto['id'] == $id && $producto['tipo'] == $tipo) { //Comprobamos si el producto es el que queremos actualizar
                // Actualizamos la cantidad del producto
                if ($action == 'increase') {
                    $producto['cantidad']++;
                } elseif ($action == 'decrease' && $producto['cantidad'] > 1) {
                    $producto['cantidad']--;
                }
                break;
            }
        }

        // Comprobamos si la cantidad del producto es 0 para eliminarlo del carrito
        $_SESSION['cart'] = array_values($_SESSION['cart']);

        header('Location: ?controller=producto&action=carrito');
        exit;
    }

    public function modificar() {
        $view = "views/modificar.php";
        include_once 'views/main.php';
    }

}
?>