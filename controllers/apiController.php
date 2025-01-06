<?php
// Mostrar errores en el servidor
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__ . '/logger.php'; // Incluimos el archivo logger.php

class ApiController {
    public function admin() {
        include_once 'api/panel_admin.php';
    }

    // Función para obtener los pedidos
    function obtenerPedidos($orderBy = null, $orderDirection = 'ASC', $excludeUnregistered = false) {
        include_once __DIR__ . '/../config/dataBase.php';
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
        // Query para obtener los pedidos
        $sql = "SELECT pedidos.id_pedido, 
                       IFNULL(usuarios.nombre, 'Este pedido fue realizado por un usuario sin registrar') AS usuario, 
                       pedidos.fecha, 
                       pedidos.total,
                       pedidos.pagado
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
    // Función para eliminar un pedido
    function eliminarPedido($id_pedido) {
        include_once __DIR__ . '/../config/dataBase.php';
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

        Logger::log("Pedido eliminado: ID $id_pedido"); // Log de la acción
    }

    // Función para obtener los pedidos de un usuario
    function crearPedido() {
        include_once __DIR__ . '/../config/dataBase.php';
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

        // Obtener usuarios
        $usuarios = [];
        $result = $conn->query("SELECT id_usuario, usuario FROM usuarios");
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }

        $conn->close();

        header('Content-Type: application/json');
        echo json_encode(['productos' => $productos, 'usuarios' => $usuarios]);
    }

    // Función para generar un pedido
    function generarPedido($productos, $codigo_promocional = null, $id_usuario = null) {
        include_once __DIR__ . '/../models/pedidosDAO.php';
        $id_pedido = pedidosDAO::guardarPedido($productos, $codigo_promocional, $id_usuario);

        header('Content-Type: application/json');
        echo json_encode(['id_pedido' => $id_pedido]);

        // Log de la acción de generar un pedido
        $productosInfo = array_map(function($producto) {
            return "Nombre: " . ($producto['nombre'] ?? 'N/A') . ", Precio: " . ($producto['precio'] ?? 'N/A') . ", Cantidad: " . ($producto['cantidad'] ?? 'N/A') . ", Tipo: " . ($producto['tipo'] ?? 'N/A');
        }, $productos);
        Logger::log("Pedido generado: ID $id_pedido, Productos: " . implode("; ", $productosInfo) . ", Código promocional: $codigo_promocional, Usuario ID: $id_usuario");
    }

    // Función para obtener los usuarios
    function obtenerUsuarios() {
        include_once __DIR__ . '/../models/usuariosDAO.php';
        $usuarios = UsuariosDAO::getAll();
        foreach ($usuarios as &$usuario) {
            $usuario['administrador'] = $usuario['administrador'] == 1 ? 'True' : 'False';
        }
        header('Content-Type: application/json');
        echo json_encode($usuarios);
    }

    function obtenerProductos() {
        include_once __DIR__ . '/../config/dataBase.php';
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

        Logger::log("Usuario creado: Usuario: " . ($input['usuario'] ?? 'N/A') . ", Nombre: " . ($input['nombre'] ?? 'N/A') . ", Apellido: " . ($input['apellido'] ?? 'N/A') . ", Email: " . ($input['email'] ?? 'N/A') . ", Teléfono: " . ($input['telefono'] ?? 'N/A'));
    }

    // Función para editar un usuario
    function editarUsuario($id_usuario) {
        include_once __DIR__ . '/../config/dataBase.php';
        $conn = DataBase::connect();
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($input['usuario'], $input['nombre'], $input['apellido'], $input['email'], $input['telefono'], $input['administrador'])) {
            $telefono = filter_var($input['telefono'], FILTER_SANITIZE_NUMBER_INT);
            if (!is_numeric($telefono)) {
                $response = array('status' => 'error', 'message' => 'El teléfono debe ser un número.');
                header('Content-Type: application/json');
                echo json_encode($response);
                return;
            }

            $sql = "UPDATE usuarios SET usuario = ?, nombre = ?, apellido = ?, email = ?, telefono = ?, administrador = ? WHERE id_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssisi", $input['usuario'], $input['nombre'], $input['apellido'], $input['email'], $telefono, $input['administrador'], $id_usuario);
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

        Logger::log("Usuario editado: ID $id_usuario, Usuario: " . ($input['usuario'] ?? 'N/A') . ", Nombre: " . ($input['nombre'] ?? 'N/A') . ", Apellido: " . ($input['apellido'] ?? 'N/A') . ", Email: " . ($input['email'] ?? 'N/A') . ", Teléfono: " . ($input['telefono'] ?? 'N/A') . ", Administrador: " . ($input['administrador'] ?? 'N/A'));
    }

    // Función para eliminar un usuario
    function eliminarUsuario($id_usuario) {
        include_once __DIR__ . '/../config/dataBase.php';
        $conn = DataBase::connect();

        if ($conn->connect_error) {
            $response = array('status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error);
            header('Content-Type: application/json');
            echo json_encode($response);
            error_log('Connection failed: ' . $conn->connect_error);
            return;
        }

        // Seleccionar los pedidos del usuario
        $sql = "SELECT id_pedido FROM pedidos WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $response = array('status' => 'error', 'message' => 'Prepare failed: ' . $conn->error);
            header('Content-Type: application/json');
            echo json_encode($response);
            error_log('Prepare failed: ' . $conn->error);
            return;
        }
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $pedidos = [];
        while ($row = $result->fetch_assoc()) {
            $pedidos[] = $row['id_pedido'];
        }
        $stmt->close();

        // Borrar productos del pedido en las tablas de relación
        $tables = ['pedido_bebida', 'pedido_complemento', 'pedido_hamburguesa', 'pedido_menu'];
        foreach ($pedidos as $id_pedido) {
            foreach ($tables as $table) {
                $sql = "DELETE FROM $table WHERE id_pedido = ?";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    $response = array('status' => 'error', 'message' => 'Prepare failed: ' . $conn->error);
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    error_log('Prepare failed: ' . $conn->error);
                    return;
                }
                $stmt->bind_param("i", $id_pedido);
                $stmt->execute();
                $stmt->close();
            }
        }

        // Borrar los pedidos del usuario de la tabla pedidos
        $sql = "DELETE FROM pedidos WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $response = array('status' => 'error', 'message' => 'Prepare failed: ' . $conn->error);
            header('Content-Type: application/json');
            echo json_encode($response);
            error_log('Prepare failed: ' . $conn->error);
            return;
        }
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $stmt->close();

        // Borrar el usuario
        $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $response = array('status' => 'error', 'message' => 'Prepare failed: ' . $conn->error);
            header('Content-Type: application/json');
            echo json_encode($response);
            error_log('Prepare failed: ' . $conn->error);
            return;
        }
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

        Logger::log("Usuario eliminado: ID $id_usuario");
    }

    // Función para crear un producto
    function crearProducto() {
        include_once __DIR__ . '/../config/dataBase.php';
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($input['nombre'], $input['descripcion'], $input['precio'], $input['imagen'], $input['tipo'])) {
            $nombre = $input['nombre'];
            $descripcion = $input['descripcion'];
            $precio = filter_var($input['precio'], FILTER_VALIDATE_FLOAT);
            $imagen = $input['imagen'];
            $tipo = $input['tipo'];
            $id_hamburguesa = isset($input['id_hamburguesa']) ? $input['id_hamburguesa'] : null;

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

            if ($tipo === 'menu' && $id_hamburguesa) {
                $stmt = $con->prepare("INSERT INTO $table (nombre, descripcion, precio, imagen, id_hamburguesa) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $imagen, $id_hamburguesa);
            } else {
                $stmt = $con->prepare("INSERT INTO $table (nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssds", $nombre, $descripcion, $precio, $imagen);
            }

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

        Logger::log("Producto creado: Nombre: " . ($input['nombre'] ?? 'N/A') . ", Descripción: " . ($input['descripcion'] ?? 'N/A') . ", Precio: " . ($input['precio'] ?? 'N/A') . ", Imagen: " . ($input['imagen'] ?? 'N/A') . ", Tipo: " . ($input['tipo'] ?? 'N/A'));
    }

    // Función para editar un producto
    function editarProducto($id_producto, $tipo) {
        include_once __DIR__ . '/../config/dataBase.php';
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

        Logger::log("Producto editado: ID $id_producto, Tipo $tipo, Nombre: " . ($input['nombre'] ?? 'N/A') . ", Descripción: " . ($input['descripcion'] ?? 'N/A') . ", Precio: " . ($input['precio'] ?? 'N/A') . ", Imagen: " . ($input['imagen'] ?? 'N/A'));
    }

    // Función para eliminar un producto
    function eliminarProducto($id_producto, $tipo) {
        include_once __DIR__ . '/../config/dataBase.php';
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

        Logger::log("Producto eliminado: ID $id_producto, Tipo $tipo");
    }

    // Función para obtener un producto
    function obtenerProducto($id_producto, $tipo) {
        include_once __DIR__ . '/../config/dataBase.php';
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
        include_once __DIR__ . '/../config/dataBase.php';
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
                
                $row['tipo'] = rtrim($row['tipo'], 's');
                $productos[] = $row;
            }

            $stmt->close();
        }

        // Obtener el estado de pagado
        $sql = "SELECT pagado FROM pedidos WHERE id_pedido = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_pedido);
        $stmt->execute();
        $stmt->bind_result($pagado);
        $stmt->fetch();
        $stmt->close();

        $conn->close();

        header('Content-Type: application/json');
        echo json_encode(['id_pedido' => $id_pedido, 'productos' => $productos, 'pagado' => $pagado]);
    }

    function actualizarPedido($id_pedido, $productos, $pagado) {
        include_once __DIR__ . '/../config/dataBase.php';
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

        // Borrar productos del pedido en las tablas de relación
        foreach ($tables as $producto_table => $pedido_table) {
            $sql = "DELETE FROM $pedido_table WHERE id_pedido = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_pedido);
            $stmt->execute();
            $stmt->close();
        }

        // Calcular el nuevo total del pedido
        $totalPedido = 0;
        foreach ($productos as $producto) {
            if (!isset($tables[$producto['tipo']])) {
                error_log("Unknown product type: " . $producto['tipo']);
                continue;
            }
            $totalPedido += $producto['precio'] * $producto['cantidad'];
        }

        if (count($productos) === 0) {
            // Borrar el pedido si no tiene productos
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

        // Calcular IVA y total
        $iva = round($totalPedido * 0.10, 2); // Assuming IVA is 10%
        $total = round($totalPedido + $iva, 2);

        // Actualizar el pedido
        $sql = "UPDATE pedidos SET pedido = ?, iva = ?, total = ?, pagado = ? WHERE id_pedido = ?";
        error_log("Updating pedidos table with SQL: $sql and values: $totalPedido, $iva, $total, $pagado, $id_pedido");
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dddii", $totalPedido, $iva, $total, $pagado, $id_pedido);
        $stmt->execute();
        $stmt->close();

        // Insertar los nuevos productos en el pedido
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

        $productosInfo = array_map(function($producto) {
            return "Nombre: " . ($producto['nombre'] ?? 'N/A') . ", Precio: " . ($producto['precio'] ?? 'N/A') . ", Cantidad: " . ($producto['cantidad'] ?? 'N/A') . ", Tipo: " . ($producto['tipo'] ?? 'N/A');
        }, $productos);
        Logger::log("Pedido actualizado: ID $id_pedido");
    }

    // Función para agregar productos a un pedido
    function agregarProductos($id_pedido, $productos) {
        include_once __DIR__ . '/../config/dataBase.php';
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

        // Insertar los nuevos productos en el pedido
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

        $productosInfo = array_map(function($producto) {
            return "Nombre: " . ($producto['nombre'] ?? 'N/A') . ", Precio: " . ($producto['precio'] ?? 'N/A') . ", Cantidad: " . ($producto['cantidad'] ?? 'N/A') . ", Tipo: " . ($producto['tipo'] ?? 'N/A');
        }, $productos);
        Logger::log("Productos agregados al pedido: ID $id_pedido, Productos: " . implode("; ", $productosInfo));
    }

    // Función para eliminar un producto de un pedido
    function eliminarProductoDePedido($id_pedido, $id_producto, $tipo) {
        include_once __DIR__ . '/../config/dataBase.php';
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
            // Revisar si el pedido tiene productos
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
                // Eliminar el pedido si no tiene productos
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

        Logger::log("Producto eliminado del pedido: ID $id_pedido, Producto ID $id_producto, Tipo $tipo");
    }

    // Función para obtener los logs
    function obtenerLogs() {
        $logs = Logger::getLogs();
        header('Content-Type: application/json');
        echo json_encode($logs);
    }
    // Función para borrar los logs
    function clearLogs() {
        Logger::clearLogs();
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Logs borrados correctamente.']);
    }

    function actualizarEstadoPagado($id_pedido, $pagado) {
        include_once __DIR__ . '/../config/dataBase.php';
        $conn = DataBase::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "UPDATE pedidos SET pagado = ? WHERE id_pedido = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $pagado, $id_pedido);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $response = array('status' => 'success', 'message' => 'Estado de pagado actualizado correctamente.');
        } else {
            $response = array('status' => 'error', 'message' => 'No se pudo actualizar el estado de pagado.');
        }

        $stmt->close();
        $conn->close();

        header('Content-Type: application/json');
        echo json_encode($response);

        Logger::log("Estado de pagado actualizado: ID $id_pedido, Pagado: $pagado");
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
                $id_usuario = isset($input['id_usuario']) ? $input['id_usuario'] : null;
                $controller->generarPedido($productos, $codigo_promocional, $id_usuario);
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
            if (isset($input['id_pedido'], $input['productos'], $input['pagado'])) {
                $controller->actualizarPedido($input['id_pedido'], $input['productos'], $input['pagado']);
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
        case 'obtenerLogs':
            $controller->obtenerLogs();
            break;
        case 'clearLogs':
            $controller->clearLogs();
            break;
        case 'actualizarEstadoPagado':
            $input = json_decode(file_get_contents('php://input'), true);
            if (isset($input['id_pedido'], $input['pagado'])) {
                $controller->actualizarEstadoPagado($input['id_pedido'], $input['pagado']);
            }
            break;
    }
}

?>
