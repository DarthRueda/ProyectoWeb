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
        include_once __DIR__ . '/../models/productosDAO.php';
        $productos = productosDAO::getAll();
        header('Content-Type: application/json');
        echo json_encode($productos);
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
    }
}

?>
