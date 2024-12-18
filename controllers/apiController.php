<?php

class ApiController {
    public function admin() {
        include_once 'api/panel_admin.html';
    }

    function obtenerPedidos($orderBy = null, $orderDirection = 'ASC', $excludeUnregistered = false) {
        include_once __DIR__ . '/../config/database.php';
        $conn = DataBase::connect();
    
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
        $orderClause = "";
        if ($orderBy) {
            $orderClause = " ORDER BY " . $orderBy . " " . $orderDirection;
        }

        $whereClause = "";
        if ($excludeUnregistered) {
            $whereClause = " WHERE pedidos.id_usuario IS NOT NULL";
        }
    
        $sql = "SELECT pedidos.id_pedido, 
                       IFNULL(usuarios.nombre, 'Este pedido fue realizado por un usuario sin registrar') AS usuario, 
                       pedidos.fecha, 
                       pedidos.total 
                FROM pedidos 
                LEFT JOIN usuarios ON pedidos.id_usuario = usuarios.id_usuario" . $whereClause . $orderClause;
        $result = $conn->query($sql);
    
        $pedidos = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $pedidos[] = $row;
            }
        }
    
        $conn->close();
    
        header('Content-Type: application/json');
        echo json_encode($pedidos);
    }

    function eliminarPedido($id_pedido) {
        include_once __DIR__ . '/../config/database.php';
        $conn = DataBase::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "DELETE FROM pedidos WHERE id_pedido = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_pedido);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $response = array('status' => 'success', 'message' => 'Pedido eliminado correctamente.');
        } else {
            $response = array('status' => 'error', 'message' => 'No se pudo eliminar el pedido.');
        }

        $stmt->close();
        $conn->close();

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function crearPedido() {
        include_once __DIR__ . '/../config/database.php';
        include_once __DIR__ . '/../models/pedidosDAO.php';
        $conn = DataBase::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $productos = [];
        $queries = [
            "SELECT id_menu AS id, nombre, precio FROM menus",
            "SELECT id_hamburguesa AS id, nombre, precio FROM hamburguesas",
            "SELECT id_bebida AS id, nombre, precio FROM bebidas",
            "SELECT id_complemento AS id, nombre, precio FROM complementos"
        ];

        foreach ($queries as $query) {
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
        }

        $conn->close();

        header('Content-Type: application/json');
        echo json_encode($productos);
    }

    function generarPedido($productos, $codigo_promocional = null) {
        include_once __DIR__ . '/../models/pedidosDAO.php';
        $id_pedido = pedidosDAO::guardarPedido($productos, $codigo_promocional);
        header('Content-Type: application/json');
        echo json_encode(['id_pedido' => $id_pedido]);
    }

    function obtenerUsuarios() {
        include_once __DIR__ . '/../models/usuariosDAO.php';
        $usuarios = UsuariosDAO::getAll();
        header('Content-Type: application/json');
        echo json_encode($usuarios);
    }

    function obtenerProductos() {
        include_once __DIR__ . '/../config/database.php';
        $conn = DataBase::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $productos = [];
        $queries = [
            "SELECT id_menu AS id, nombre, descripcion, precio, imagen, 'menu' AS tipo FROM menus",
            "SELECT id_hamburguesa AS id, nombre, descripcion, precio, imagen, 'hamburguesa' AS tipo FROM hamburguesas",
            "SELECT id_bebida AS id, nombre, descripcion, precio, imagen, 'bebida' AS tipo FROM bebidas",
            "SELECT id_complemento AS id, nombre, descripcion, precio, imagen, 'complemento' AS tipo FROM complementos"
        ];

        foreach ($queries as $query) {
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
        }

        if (isset($_GET['tipo'])) {
            $tipo = $_GET['tipo'];
            $productos = array_filter($productos, function($producto) use ($tipo) {
                return $producto['tipo'] === $tipo;
            });
        }

        $conn->close();

        header('Content-Type: application/json');
        echo json_encode(array_values($productos));
    }

    function crearUsuario() {
        include_once __DIR__ . '/../models/usuariosDAO.php';
        include_once __DIR__ . '/../models/usuario.php';
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($input['usuario'], $input['nombre'], $input['apellido'], $input['email'], $input['contrasena'], $input['telefono'])) {
            $telefono = filter_var($input['telefono'], FILTER_SANITIZE_NUMBER_INT);
            if (!is_numeric($telefono)) {
                $response = array('status' => 'error', 'message' => 'El teléfono debe ser un número.');
                header('Content-Type: application/json');
                echo json_encode($response);
                return;
            }

            $usuario = new Usuario(
                $input['usuario'],
                $input['nombre'],
                $input['apellido'],
                password_hash($input['contrasena'], PASSWORD_BCRYPT),
                $input['email'],
                $telefono
            );

            $result = UsuariosDAO::insert($usuario);

            if ($result) {
                $response = array('status' => 'success', 'message' => 'Usuario creado correctamente.');
            } else {
                $response = array('status' => 'error', 'message' => 'No se pudo crear el usuario.');
            }
        } else {
            $response = array('status' => 'error', 'message' => 'Datos incompletos.');
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function eliminarUsuario($id_usuario) {
        include_once __DIR__ . '/../config/database.php';
        $conn = DataBase::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $response = array('status' => 'success', 'message' => 'Usuario eliminado correctamente.');
        } else {
            $response = array('status' => 'error', 'message' => 'No se pudo eliminar el usuario.');
        }

        $stmt->close();
        $conn->close();

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function crearProducto() {
        include_once __DIR__ . '/../config/database.php';
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($input['nombre'], $input['descripcion'], $input['precio'], $input['imagen'], $input['tipo'])) {
            $nombre = $input['nombre'];
            $descripcion = $input['descripcion'];
            $precio = filter_var($input['precio'], FILTER_VALIDATE_FLOAT);
            $imagen = $input['imagen'];
            $tipo = $input['tipo'];

            if ($precio === false) {
                $response = array('status' => 'error', 'message' => 'El precio debe ser un número decimal.');
                header('Content-Type: application/json');
                echo json_encode($response);
                return;
            }

            $con = DataBase::connect();
            if ($con->connect_error) {
                die("Connection failed: " . $con->connect_error);
            }

            $table = '';
            switch ($tipo) {
                case 'menu':
                    $table = 'menus';
                    break;
                case 'hamburguesa':
                    $table = 'hamburguesas';
                    break;
                case 'bebida':
                    $table = 'bebidas';
                    break;
                case 'complemento':
                    $table = 'complementos';
                    break;
                default:
                    $response = array('status' => 'error', 'message' => 'Tipo de producto no válido.');
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    return;
            }

            $stmt = $con->prepare("INSERT INTO $table (nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $nombre, $descripcion, $precio, $imagen);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $response = array('status' => 'success', 'message' => 'Producto creado correctamente.');
            } else {
                $response = array('status' => 'error', 'message' => 'No se pudo crear el producto.');
            }

            $stmt->close();
            $con->close();
        } else {
            $response = array('status' => 'error', 'message' => 'Datos incompletos.');
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function eliminarProducto($id_producto, $tipo) {
        include_once __DIR__ . '/../config/database.php';
        $conn = DataBase::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $table = '';
        switch ($tipo) {
            case 'menu':
                $table = 'menus';
                break;
            case 'hamburguesa':
                $table = 'hamburguesas';
                break;
            case 'bebida':
                $table = 'bebidas';
                break;
            case 'complemento':
                $table = 'complementos';
                break;
            default:
                $response = array('status' => 'error', 'message' => 'Tipo de producto no válido.');
                header('Content-Type: application/json');
                echo json_encode($response);
                return;
        }

        $sql = "DELETE FROM $table WHERE id_$tipo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $response = array('status' => 'success', 'message' => 'Producto eliminado correctamente.');
        } else {
            $response = array('status' => 'error', 'message' => 'No se pudo eliminar el producto.');
        }

        $stmt->close();
        $conn->close();

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

if (isset($_GET['action'])) {
    $controller = new ApiController();
    switch ($_GET['action']) {
        case 'obtenerPedidos':
            $controller->obtenerPedidos();
            break;
        case 'ordenarPorUsuario':
            $controller->obtenerPedidos('usuario', 'ASC', true);
            break;
        case 'ordenarPorFecha':
            $controller->obtenerPedidos('fecha', 'DESC');
            break;
        case 'ordenarPorPrecio':
            $controller->obtenerPedidos('total');
            break;
        case 'eliminarPedido':
            if (isset($_GET['id_pedido'])) {
                $controller->eliminarPedido($_GET['id_pedido']);
            }
            break;
        case 'crearPedido':
            $controller->crearPedido();
            break;
        case 'generarPedido':
            $input = json_decode(file_get_contents('php://input'), true);
            if (isset($input['productos'])) {
                $productos = $input['productos'];
                $codigo_promocional = isset($input['codigo_promocional']) ? $input['codigo_promocional'] : null;
                $controller->generarPedido($productos, $codigo_promocional);
            }
            break;
        case 'obtenerUsuarios':
            $controller->obtenerUsuarios();
            break;
        case 'obtenerProductos':
            $controller->obtenerProductos();
            break;
        case 'crearUsuario':
            $controller->crearUsuario();
            break;
        case 'eliminarUsuario':
            if (isset($_GET['id_usuario'])) {
                $controller->eliminarUsuario($_GET['id_usuario']);
            }
            break;
        case 'crearProducto':
            $controller->crearProducto();
            break;
        case 'eliminarProducto':
            if (isset($_GET['id_producto'], $_GET['tipo'])) {
                $controller->eliminarProducto($_GET['id_producto'], $_GET['tipo']);
            }
            break;
    }
}

?>
