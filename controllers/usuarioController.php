<?php
include_once("models/usuario.php");
include_once("models/usuariosDAO.php");

class usuarioController{
    //Funciones para controlar las vistas de los usuarios
    public function login(){
        $view = "views/login.php";
        include_once 'views/main.php';
    }
    public function registro(){
        $view = "views/registro.php";
        include_once 'views/main.php';
    }
    public function menu_usuario(){
        $view = "views/menu_usuario.php";
        include_once 'views/main.php';
    }

    public function pedidos_info() {
        $view = "views/pedidos_info.php";
        include_once 'views/main.php';
    }
    
    public function cerrar_sesion(){
        session_start();
        session_unset();
        session_destroy();
        header("Location: index.php");
    }
    
    //Acrtualizar datos del usuario
    public function actualizar_datos(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            include_once ("config/dataBase.php");
            $con = DataBase::connect();
            $id_usuario = $con->real_escape_string($_POST['id_usuario']);
            $apellido = $con->real_escape_string($_POST['apellido']);
            $email = $con->real_escape_string($_POST['email']);
            $telefono = $con->real_escape_string($_POST['telefono']);
            $direccion = $con->real_escape_string($_POST['direccion']);

            $sql = "UPDATE usuarios SET apellido='$apellido', email='$email', telefono='$telefono', direccion='$direccion' WHERE id_usuario='$id_usuario'";
            if ($con->query($sql) === TRUE) {
                $_SESSION['apellido'] = $apellido;
                $_SESSION['email'] = $email;
                $_SESSION['telefono'] = $telefono;
                $_SESSION['direccion'] = $direccion;
                header("Location: index.php?controller=usuario&action=menu_usuario");
                exit;
            } else {
                echo "Error: " . $sql . "<br>" . $con->error;
            }
            $con->close();
        }
    }

    //Eliminar usuario
    public function eliminar_usuario() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id_usuario'])) {
            include_once ("config/dataBase.php");
            $con = DataBase::connect();
            $id_usuario = $_SESSION['id_usuario'];

            // Eliminar pedidos del usuario
            $query = "DELETE FROM pedidos WHERE id_usuario = ?";
            $stmt = $con->prepare($query);
            if (!$stmt) {
                die("Error: " . $con->error);
            }
            $stmt->bind_param('i', $id_usuario);
            if (!$stmt->execute()) {
                die("Error: " . $stmt->error);
            }
            $stmt->close();

            // Eliminar usuario
            $query = "DELETE FROM usuarios WHERE id_usuario = ?";
            $stmt = $con->prepare($query);
            if (!$stmt) {
                die("Error: " . $con->error);
            }
            $stmt->bind_param('i', $id_usuario);
            if (!$stmt->execute()) {
                die("Error: " . $stmt->error);
            }
            $stmt->close();

            // Cerrar sesión
            session_unset();
            session_destroy();

            $con->close(); // Cerrar conexión

            header("Location: index.php");
            exit;
        } else {
            die("La sesion actual no tiene un usuario ID."); // Si no hay un usuario ID en la sesión actual
        }
    }

    //Redirigir a la página de registro
    public function rediriguirRegistro() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $email = $_POST['email'];
            header("Location: index.php?controller=usuario&action=registro&nombre=$nombre&apellido=$apellido&email=$email");
            exit;
        }
    }

    //Funcion que sirve para pedir el ultimo pedido del usuario
    public function pedir_pedido_anterior() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id_usuario'])) {
            $id_usuario = $_SESSION['id_usuario'];
            $pedido = pedidosDAO::getLatestPedidoByUsuarioId($id_usuario);
            if ($pedido) {
                $_SESSION['cart'] = pedidosDAO::getProductosByPedidoId($pedido['id_pedido']);
                header("Location: index.php?controller=producto&action=compra");
                exit;
            } else {
                echo "No se encontró ningún pedido anterior.";
            }
        } else {
            echo "Usuario no autenticado.";
        }
    }


}
?>