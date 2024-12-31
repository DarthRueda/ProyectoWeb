<?php
include_once("models/usuario.php");
include_once("models/usuariosDAO.php");

class usuarioController{
    //Funciones para controlar las vistas de los usuarios
    public function login(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = $_POST['usuario'];
            $password = $_POST['password'];
            $user = UsuariosDAO::getUserByUsername($usuario);

            if ($user && password_verify($password, $user['contrasena'])) {
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['id_usuario'] = $user['id_usuario'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['apellido'] = $user['apellido'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['telefono'] = $user['telefono'];
                $_SESSION['direccion'] = $user['direccion'];
                header("Location: index.php?controller=usuario&action=menu_usuario");
                exit;
            } else {
                $error = "Nombre o contraseña incorrectos. Por favor, inténtelo de nuevo o regístrese si no tiene una cuenta.";
            }
        }
        $view = "views/login.php";
        include_once 'views/main.php';
    }

    public function registro(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = $_POST['usuario'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptador
            $telefono = $_POST['telefono'];

            $user = new Usuario($usuario, $nombre, $apellido, $password, $email, $telefono);
            if (UsuariosDAO::insert($user)) {
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['id_usuario'] = UsuariosDAO::getUserByUsername($usuario)['id_usuario'];
                $_SESSION['nombre'] = $nombre;
                $_SESSION['apellido'] = $apellido;
                $_SESSION['email'] = $email;
                $_SESSION['telefono'] = $telefono;
                $_SESSION['direccion'] = null;
                echo "<div id='registro-exitoso' style='position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: green; color: white; padding: 20px; font-size: 24px; text-align: center; border-radius: 10px;'>Registro exitoso</div>";
                echo "<script>
                        setTimeout(function() { 
                            document.getElementById('registro-exitoso').style.display = 'none'; 
                            window.location.href = 'index.php?controller=usuario&action=menu_usuario';
                        }, 1000);
                      </script>";
            } else {
                echo "Error: No se pudo completar el registro.";
            }
        }
        $view = "views/registro.php";
        include_once 'views/main.php';
    }

    public function menu_usuario(){
        session_start();
        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
            header("Location: index.php?controller=usuario&action=login");
            exit;
        }

        $editing = isset($_GET['edit']) && $_GET['edit'] == 'true';
        $isAdmin = false;
        if (isset($_SESSION['id_usuario'])) {
            $isAdmin = UsuariosDAO::isAdmin($_SESSION['id_usuario']);
        }

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
                $productos = pedidosDAO::getProductosByPedidoId($pedido['id_pedido']);
                $_SESSION['cart_data'] = $productos;
                $_SESSION['id_pedido'] = pedidosDAO::guardarPedido($productos);
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