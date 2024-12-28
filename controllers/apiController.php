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

        // Borrar productos del pedido en las tablas de relación
        $tables = ['pedido_bebida', 'pedido_complemento', 'pedido_hamburguesa', 'pedido_menu'];
        foreach ($tables as $table) {
            $sql = "DELETE FROM $table WHERE id_pedido = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_pedido);
            $stmt->execute();
            $stmt->close();
        }

        // Delete from pedidos table
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
            "SELECT id_menu AS id, nombre, precio, 'menu' AS tipo FROM menus",
            "SELECT id_hamburguesa AS id, nombre, precio, 'hamburguesa' AS tipo FROM hamburguesas",
            "SELECT id_bebida AS id, nombre, precio, 'bebida' AS tipo FROM bebidas",
            "SELECT id_complemento AS id, nombre, precio, 'complemento' AS tipo FROM complementos"
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

    // Función para editar un usuario
    function editarUsuario($id_usuario) {
        include_once __DIR__ . '/../config/database.php';
        $conn = DataBase::connect();
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($input['usuario'], $input['nombre'], $input['apellido'], $input['email'], $input['telefono'])) {
            $telefono = filter_var($input['telefono'], FILTER_SANITIZE_NUMBER_INT);
            if (!is_numeric($telefono)) {
                $response = array('status' => 'error', 'message' => 'El teléfono debe ser un número.');
                header('Content-Type: application/json');
                echo json_encode($response);
                return;
            }

            $contrasena = $input['contrasena'] ? password_hash($input['contrasena'], PASSWORD_BCRYPT) : null;

            $sql = "UPDATE usuarios SET usuario = ?, nombre = ?, apellido = ?, email = ?, telefono = ?";
            if ($contrasena) {
                $sql .= ", contrasena = ?";
            }
            $sql .= " WHERE id_usuario = ?";

            $stmt = $conn->prepare($sql);
            if ($contrasena) {
                $stmt->bind_param("ssssssi", $input['usuario'], $input['nombre'], $input['apellido'], $input['email'], $telefono, $contrasena, $id_usuario);
            } else {
                $stmt->bind_param("sssssi", $input['usuario'], $input['nombre'], $input['apellido'], $input['email'], $telefono, $id_usuario);
            }
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $response = array('status' => 'success', 'message' => 'Usuario editado correctamente.');
            } else {
                $response = array('status' => 'error', 'message' => 'No se pudo editar el usuario.');
            }

            $stmt->close();
        } else {
            $response = array('status' => 'error', 'message' => 'Datos incompletos.');
        }

        $conn->close();
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    // Función para eliminar un usuario
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

    // Función para crear un producto
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

    // Función para editar un producto
    function editarProducto($id_producto, $tipo) {
        include_once __DIR__ . '/../config/database.php';
        $conn = DataBase::connect();
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($input['nombre'], $input['descripcion'], $input['precio'], $input['imagen'])) {
            $nombre = $input['nombre'];
            $descripcion = $input['descripcion'];
            $precio = filter_var($input['precio'], FILTER_VALIDATE_FLOAT);
            $imagen = $input['imagen'];

            if ($precio === false) {
                $response = array('status' => 'error', 'message' => 'El precio debe ser un número decimal.');
                header('Content-Type: application/json');
                echo json_encode($response);
                return;
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

            $sql = "UPDATE $table SET nombre = ?, descripcion = ?, precio = ?, imagen = ? WHERE id_$tipo = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $imagen, $id_producto);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $response = array('status' => 'success', 'message' => 'Producto editado correctamente.');
            } else {
                $response = array('status' => 'error', 'message' => 'No se pudo editar el producto.');
            }

            $stmt->close();
        } else {
            $response = array('status' => 'error', 'message' => 'Datos incompletos.');
        }

        $conn->close();
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    // Función para eliminar un producto
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

    // Función para obtener un producto
    function obtenerProducto($id_producto, $tipo) {
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

        $sql = "SELECT nombre, descripcion, precio, imagen FROM $table WHERE id_$tipo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $producto = $result->fetch_assoc();
            $response = array('status' => 'success', 'producto' => $producto);
        } else {
            $response = array('status' => 'error', 'message' => 'Producto no encontrado.');
        }

        $stmt->close();
        $conn->close();

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function editarPedido($id_pedido) {
        include_once __DIR__ . '/../config/database.php';
        $conn = DataBase::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $productos = [];
        $tables = [
            'pedido_hamburguesa' => 'hamburguesas',
            'pedido_bebida' => 'bebidas',
            'pedido_complemento' => 'complementos',
            'pedido_menu' => 'menus'
        ];

        foreach ($tables as $pedido_table => $producto_table) {
            $id_column = str_replace('pedido_', 'id_', $pedido_table);
            $sql = "SELECT $producto_table.$id_column AS id, $producto_table.nombre, $producto_table.precio, '$producto_table' AS tipo, COUNT($pedido_table.$id_column) AS cantidad
                    FROM $pedido_table
                    JOIN $producto_table ON $pedido_table.$id_column = $producto_table.$id_column
                    WHERE $pedido_table.id_pedido = ?
                    GROUP BY $producto_table.$id_column";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_pedido);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                // Convert plural tipo to singular
                $row['tipo'] = rtrim($row['tipo'], 's');
                $productos[] = $row;
            }

            $stmt->close();
        }

        $conn->close();

        header('Content-Type: application/json');
        echo json_encode(['id_pedido' => $id_pedido, 'productos' => $productos]);
    }

    function actualizarPedido($id_pedido, $productos) {
        include_once __DIR__ . '/../config/database.php';
        $conn = DataBase::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $tables = [
            'hamburguesa' => 'pedido_hamburguesa',
            'bebida' => 'pedido_bebida',
            'complemento' => 'pedido_complemento',
            'menu' => 'pedido_menu'
        ];

        // Delete existing products from the pedido
        foreach ($tables as $producto_table => $pedido_table) {
            $sql = "DELETE FROM $pedido_table WHERE id_pedido = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_pedido);
            $stmt->execute();
            $stmt->close();
        }

        // Calculate the new total price
        $totalPedido = 0;
        foreach ($productos as $producto) {
            if (!isset($tables[$producto['tipo']])) {
                error_log("Unknown product type: " . $producto['tipo']);
                continue;
            }
            $totalPedido += $producto['precio'] * $producto['cantidad'];
        }

        if (count($productos) === 0) {
            // Delete the pedido if there are no products
            $sql = "DELETE FROM pedidos WHERE id_pedido = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_pedido);
            $stmt->execute();
            $stmt->close();
            $conn->close();

            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Pedido eliminado porque no tiene productos.']);
            return;
        }

        // Calculate IVA and total
        $iva = round($totalPedido * 0.10, 2); // Assuming IVA is 10%
        $total = round($totalPedido + $iva, 2);

        // Update the pedidos table
        $sql = "UPDATE pedidos SET pedido = ?, iva = ?, total = ? WHERE id_pedido = ?";
        error_log("Updating pedidos table with SQL: $sql and values: $totalPedido, $iva, $total, $id_pedido");
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dddi", $totalPedido, $iva, $total, $id_pedido);
        $stmt->execute();
        $stmt->close();

        // Insert new products into the pedido
        foreach ($productos as $producto) {
            if (!isset($tables[$producto['tipo']])) {
                error_log("Unknown product type: " . $producto['tipo']);
                continue;
            }
            $pedido_table = $tables[$producto['tipo']];
            $id_column = 'id_' . $producto['tipo'];
            $sql = "INSERT INTO $pedido_table (id_pedido, $id_column) VALUES (?, ?)";
            error_log("Executing SQL: $sql with id_pedido: $id_pedido and id: " . $producto['id']);
            $stmt = $conn->prepare($sql);
            for ($i = 0; $i < $producto['cantidad']; $i++) {
                $stmt->bind_param("ii", $id_pedido, $producto['id']);
                $stmt->execute();
            }
            $stmt->close();
        }

        $conn->close();

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Pedido actualizado correctamente.']);
    }

    function agregarProductos($id_pedido, $productos) {
        include_once __DIR__ . '/../config/database.php';
        $conn = DataBase::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $tables = [
            'hamburguesa' => 'pedido_hamburguesa',
            'bebida' => 'pedido_bebida',
            'complemento' => 'pedido_complemento',
            'menu' => 'pedido_menu'
        ];

        // Insert new products into the pedido
        foreach ($productos as $producto) {
            if (!isset($tables[$producto['tipo']])) {
                error_log("Unknown product type: " . $producto['tipo']);
                continue;
            }
            $pedido_table = $tables[$producto['tipo']];
            $id_column = 'id_' . $producto['tipo'];
            $sql = "INSERT INTO $pedido_table (id_pedido, $id_column) VALUES (?, ?)";
            error_log("Executing SQL: $sql with id_pedido: $id_pedido and id: " . $producto['id']);
            $stmt = $conn->prepare($sql);
            for ($i = 0; $i < $producto['cantidad']; $i++) {
                $stmt->bind_param("ii", $id_pedido, $producto['id']);
                $stmt->execute();
            }
            $stmt->close();
        }

        $conn->close();

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Productos agregados correctamente.']);
    }

    function eliminarProductoDePedido($id_pedido, $id_producto, $tipo) {
        include_once __DIR__ . '/../config/database.php';
        $conn = DataBase::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        error_log("eliminarProductoDePedido called with tipo: $tipo");

        $table = '';
        switch ($tipo) {
            case 'hamburguesa':
                $table = 'pedido_hamburguesa';
                break;
            case 'bebida':
                $table = 'pedido_bebida';
                break;
            case 'complemento':
                $table = 'pedido_complemento';
                break;
            case 'menu':
                $table = 'pedido_menu';
                break;
            default:
                $response = array('status' => 'error', 'message' => 'Tipo de producto no válido.');
                header('Content-Type: application/json');
                echo json_encode($response);
                return;
        }

        $sql = "DELETE FROM $table WHERE id_pedido = ? AND id_$tipo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_pedido, $id_producto);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Check if there are any products left in the pedido
            $sql = "SELECT COUNT(*) as count FROM (
                        SELECT id_pedido FROM pedido_hamburguesa WHERE id_pedido = ?
                        UNION ALL
                        SELECT id_pedido FROM pedido_bebida WHERE id_pedido = ?
                        UNION ALL
                        SELECT id_pedido FROM pedido_complemento WHERE id_pedido = ?
                        UNION ALL
                        SELECT id_pedido FROM pedido_menu WHERE id_pedido = ?
                    ) as productos";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiii", $id_pedido, $id_pedido, $id_pedido, $id_pedido);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['count'] == 0) {
                // Delete the pedido if there are no products left
                $sql = "DELETE FROM pedidos WHERE id_pedido = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id_pedido);
                $stmt->execute();
                $response = array('status' => 'success', 'message' => 'Pedido eliminado porque no tiene productos.');
            } else {
                $response = array('status' => 'success', 'message' => 'Producto eliminado correctamente.');
            }
        } else {
            $response = array('status' => 'error', 'message' => 'No se pudo eliminar el producto.');
        }

        $stmt->close();
        $conn->close();

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}


//Switch para las acciones de la API
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
        case 'editarUsuario':
            if (isset($_GET['id_usuario'])) {
                $controller->editarUsuario($_GET['id_usuario']);
            }
            break;
        case 'eliminarUsuario':
            if (isset($_GET['id_usuario'])) {
                $controller->eliminarUsuario($_GET['id_usuario']);
            }
            break;
        case 'crearProducto':
            $controller->crearProducto();
            break;
        case 'editarProducto':
            if (isset($_GET['id_producto'], $_GET['tipo'])) {
                $controller->editarProducto($_GET['id_producto'], $_GET['tipo']);
            }
            break;
        case 'eliminarProducto':
            if (isset($_GET['id_producto'], $_GET['tipo'])) {
                $controller->eliminarProducto($_GET['id_producto'], $_GET['tipo']);
            }
            break;
        case 'obtenerProducto':
            if (isset($_GET['id_producto'], $_GET['tipo'])) {
                $controller->obtenerProducto($_GET['id_producto'], $_GET['tipo']);
            }
            break;
        case 'editarPedido':
            if (isset($_GET['id_pedido'])) {
                $controller->editarPedido($_GET['id_pedido']);
            }
            break;
        case 'actualizarPedido':
            $input = json_decode(file_get_contents('php://input'), true);
            if (isset($input['id_pedido'], $input['productos'])) {
                $controller->actualizarPedido($input['id_pedido'], $input['productos']);
            }
            break;
        case 'agregarProductos':
            $input = json_decode(file_get_contents('php://input'), true);
            if (isset($input['id_pedido'], $input['productos'])) {
                $controller->agregarProductos($input['id_pedido'], $input['productos']);
            }
            break;
        case 'eliminarProductoDePedido':
            if (isset($_GET['id_pedido'], $_GET['id_producto'], $_GET['tipo'])) {
                $controller->eliminarProductoDePedido($_GET['id_pedido'], $_GET['id_producto'], $_GET['tipo']);
            }
            break;
    }
}

?>
