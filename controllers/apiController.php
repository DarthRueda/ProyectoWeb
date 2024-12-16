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
}

// Check if action parameter is set and call the appropriate function
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
    }
}

?>
