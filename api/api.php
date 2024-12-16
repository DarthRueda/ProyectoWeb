<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once("../config/dataBase.php");

$metodo = $_SERVER["REQUEST_METHOD"];
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';
$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : '';

switch($metodo){
    case 'GET':
        if ($endpoint == 'pedidos') {
            $con = DataBase::connect();
            $orderClause = '';
            if ($orderBy) {
                $orderClause = "ORDER BY " . $con->real_escape_string($orderBy);
            }
            if (isset($_GET['id_pedido'])) {
                $id_pedido = $con->real_escape_string($_GET['id_pedido']);
                $query = "SELECT * FROM pedidos WHERE id_pedido = '$id_pedido'";
                $result = $con->query($query);
                if ($result->num_rows > 0) {
                    $pedido = $result->fetch_assoc();
                    echo json_encode([
                        "estado" => "Exito",
                        "data" => $pedido
                    ]);
                } else {
                    echo json_encode([
                        "estado" => "Error",
                        "data" => "Pedido no encontrado"
                    ]);
                }
            } else {
                $query = "SELECT p.id_pedido, p.id_usuario, p.fecha, p.total, u.usuario 
                          FROM pedidos p 
                          LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario $orderClause";
                $result = $con->query($query);

                $pedidos = [];
                while ($row = $result->fetch_assoc()) {
                    $pedidos[] = $row;
                }

                echo json_encode([
                    "estado" => "Exito",
                    "data" => $pedidos
                ]);
            }
            $con->close();
        } else {
            if (isset($_GET["id"])){
                $existe = false;
                foreach($users as $user){
                    if ($user["id"] == $_GET["id"]){
                        echo json_encode([
                            "estado" => "Exito",
                            "data" => $user
                        ]);
                        $existe = true;
                        break;
                    }
                }
                if (!$existe){
                    http_response_code(404);
                    echo json_encode([
                        "estado" => "Error",
                        "data" => "Usuario no encontrado"
                    ]);
                }
            }else{
                echo json_encode([
                    "estado" => "Exito",
                    "data" => $users
                ]);
            }
        }
        if ($endpoint == 'productos') {
            $con = DataBase::connect();
            $query = "
                SELECT id_menu AS id, nombre, descripcion, precio, imagen, 'menus' AS tipo FROM menus
                UNION
                SELECT id_hamburguesa AS id, nombre, descripcion, precio, imagen, 'hamburguesa' AS tipo FROM hamburguesas
                UNION
                SELECT id_bebida AS id, nombre, descripcion, precio, imagen, 'bebida' AS tipo FROM bebidas
                UNION
                SELECT id_complemento AS id, nombre, descripcion, precio, imagen, 'complemento' AS tipo FROM complementos
            ";
            $result = $con->query($query);

            $productos = [];
            while ($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }

            echo json_encode([
                "estado" => "Exito",
                "data" => $productos
            ]);

            $con->close();
        }
    break;

    case 'DELETE':
        if ($endpoint == 'pedidos' && isset($_GET['id_pedido'])) {
            $con = DataBase::connect();
            $id_pedido = $con->real_escape_string($_GET['id_pedido']);
            $query = "DELETE FROM pedidos WHERE id_pedido = '$id_pedido'";
            if ($con->query($query) === TRUE) {
                echo json_encode([
                    "estado" => "Exito",
                    "data" => "Pedido eliminado con éxito"
                ]);
            } else {
                echo json_encode([
                    "estado" => "Error",
                    "data" => "Error al eliminar el pedido"
                ]);
            }
            $con->close();
        }
    break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        
        array_push($users, 
        [
            "id" => count($users) + 1,
            "name" => $data["name"],
            "team" => $data["team"]
        ]);

        echo json_encode([
            "estado" => "Exito",
            "data" => "Usuario agregado con exito"
        ]);
        if ($endpoint == 'pedidos') {
            $data = json_decode(file_get_contents("php://input"), true);
            $productos = $data['productos'];
            $id_usuario = $data['id_usuario'];

            $con = DataBase::connect();
            $fecha = date('Y-m-d H:i:s');
            $query = "INSERT INTO pedidos (pedido, iva, total, id_oferta, id_usuario, fecha) VALUES (0, 0, 0, NULL, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param('is', $id_usuario, $fecha);
            $stmt->execute();
            $id_pedido = $stmt->insert_id;
            $stmt->close();

            $pedido = 0;
            foreach ($productos as $producto) {
                $pedido += $producto['precio'];
                $query = "INSERT INTO pedido_{$producto['tipo']} (id_pedido, id_{$producto['tipo']}) VALUES (?, ?)";
                $stmt = $con->prepare($query);
                $stmt->bind_param('ii', $id_pedido, $producto['id']);
                $stmt->execute();
                $stmt->close();
            }

            $iva = round($pedido * 0.10, 2);
            $total = $pedido + $iva;

            $query = "UPDATE pedidos SET pedido = ?, iva = ?, total = ? WHERE id_pedido = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('dddi', $pedido, $iva, $total, $id_pedido);
            $stmt->execute();
            $stmt->close();

            $con->close();
            echo json_encode([
                "estado" => "Exito",
                "data" => "Pedido creado con éxito"
            ]);
        }
    break;
}

?>