<?php
include_once __DIR__ . '/../config/dataBase.php';

class pedidosDAO {
    public static function guardarPedido($productos, $codigo_promocional = null) {
        $con = DataBase::connect();
        $fecha = date('Y-m-d H:i:s'); // Fecha actual
        
        // Insertar valores en la base de datos
        $query = "INSERT INTO pedidos (pedido, iva, total, id_oferta, id_usuario, fecha) VALUES (0, 0, 0, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $id_oferta = null; // No hay ofertas implementadas por defecto
        $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null; // Obtener id_usuario si está logueado
        $stmt->bind_param('iis', $id_oferta, $id_usuario, $fecha);
        $stmt->execute();
        $id_pedido = $stmt->insert_id;
        $stmt->close();

        // Calcular el total del pedido
        $pedido = 0;
        foreach ($productos as $producto) {
            $pedido += $producto['precio'] * $producto['cantidad'];
            for ($i = 0; $i < $producto['cantidad']; $i++) {
                if (isset($producto['id_menu'])) {
                    $query = "INSERT INTO pedido_menu (id_pedido, id_menu) VALUES (?, ?)";
                    $stmt = $con->prepare($query);
                    $stmt->bind_param('ii', $id_pedido, $producto['id_menu']);
                } elseif (isset($producto['id_hamburguesa'])) {
                    $query = "INSERT INTO pedido_hamburguesa (id_pedido, id_hamburguesa) VALUES (?, ?)";
                    $stmt = $con->prepare($query);
                    $stmt->bind_param('ii', $id_pedido, $producto['id_hamburguesa']);
                } elseif (isset($producto['id_bebida'])) {
                    $query = "INSERT INTO pedido_bebida (id_pedido, id_bebida) VALUES (?, ?)";
                    $stmt = $con->prepare($query);
                    $stmt->bind_param('ii', $id_pedido, $producto['id_bebida']);
                } elseif (isset($producto['id_complemento'])) {
                    $query = "INSERT INTO pedido_complemento (id_pedido, id_complemento) VALUES (?, ?)";
                    $stmt = $con->prepare($query);
                    $stmt->bind_param('ii', $id_pedido, $producto['id_complemento']);
                } else {
                    continue;
                }
                $stmt->execute();
                $stmt->close();
            }
        }

        // Calcular el IVA
        $iva = round($pedido * 0.10, 2);
        $total = $pedido + $iva;

        // Aplicar descuento si hay un código promocional válido en la fecha actual
        $descuento = 0;
        if ($codigo_promocional) {
            $query = "SELECT id_oferta, descuento, fecha_inicio, fecha_fin FROM ofertas WHERE nombre = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('s', $codigo_promocional);
            $stmt->execute();
            $stmt->bind_result($id_oferta, $descuento, $fecha_inicio, $fecha_fin);
            if ($stmt->fetch()) {
                $fecha_actual = date('Y-m-d');
                if ($fecha_actual >= $fecha_inicio && $fecha_actual <= $fecha_fin) {
                    $stmt->close();
                    $query = "UPDATE pedidos SET id_oferta = ? WHERE id_pedido = ?";
                    $stmt = $con->prepare($query);
                    $stmt->bind_param('ii', $id_oferta, $id_pedido);
                    $stmt->execute();
                    $stmt->close();
                    $descuento = round($total * ($descuento / 100), 2);
                } else {
                    $descuento = 0; // No aplicar descuento si la fecha no es válida
                    $stmt->close();
                }
            } else {
                $stmt->close();
            }
        }

        // Calcular el total con descuento
        $total = round($total - $descuento, 2);

        // Actualizar el pedido, IVA y total del pedido
        $query = "UPDATE pedidos SET pedido = ?, iva = ?, total = ? WHERE id_pedido = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('dddi', $pedido, $iva, $total, $id_pedido);
        $stmt->execute();
        $stmt->close();

        $con->close();
        return $id_pedido; // Devolver el ID del pedido
    }

    public static function getTotalByPedidoId($id_pedido) {
        $con = DataBase::connect();
        $query = "SELECT total FROM pedidos WHERE id_pedido = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id_pedido);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();
        $con->close();
        return $total;
    }

    public static function getIvaByPedidoId($id_pedido) {
        $con = DataBase::connect();
        $query = "SELECT iva FROM pedidos WHERE id_pedido = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id_pedido);
        $stmt->execute();
        $stmt->bind_result($iva);
        $stmt->fetch();
        $stmt->close();
        $con->close();
        return $iva;
    }

    public static function getPedidoByPedidoId($id_pedido) {
        $con = DataBase::connect();
        $query = "SELECT pedido FROM pedidos WHERE id_pedido = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id_pedido);
        $stmt->execute();
        $stmt->bind_result($pedido);
        $stmt->fetch();
        $stmt->close();
        $con->close();
        return $pedido;
    }

    public static function getDescuentoByPedidoId($id_pedido) {
        $con = DataBase::connect();
        $query = "SELECT id_oferta FROM pedidos WHERE id_pedido = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id_pedido);
        $stmt->execute();
        $stmt->bind_result($id_oferta);
        $stmt->fetch();
        $stmt->close();

        if ($id_oferta) {
            $query = "SELECT descuento FROM ofertas WHERE id_oferta = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('i', $id_oferta);
            $stmt->execute();
            $stmt->bind_result($descuento);
            $stmt->fetch();
            $stmt->close();
            $con->close();
            return $descuento;
        }

        $con->close();
        return 0;
    }

    //Con esto se marca el pedido como pagado en la base de datos
    public static function marcarPedidoComoPagado($id_pedido) {
        $con = DataBase::connect();
        $query = "UPDATE pedidos SET pagado = 1 WHERE id_pedido = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id_pedido);
        $stmt->execute();
        $stmt->close();
        $con->close();
    }

    //Con esto se obtienen los pedidos de un usuario (FASE DE PRUEBAS)
    public static function getPedidosByUsuarioId($id_usuario) {
        $con = DataBase::connect();
        $query = "SELECT id_pedido, total FROM pedidos WHERE id_usuario = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $pedidos = $result->fetch_all(MYSQLI_ASSOC); //Obtener todos los resultados
        $stmt->close();
        $con->close();
        return $pedidos;
    }

    //Con esto se obtienen los productos de un pedido (FASE DE PRUEBAS)
    // NOTA: ESTA FUNCIÓN PODRIA SER SOLO EXCLUSIVA DE LOS USUARIOS CON RANGO DE ADMINISTRADOR
    public static function getProductosByPedidoId($id_pedido) {
        $con = DataBase::connect();
        $productos = [];

        $queries = [
            "SELECT m.nombre, m.precio FROM pedido_menu pm JOIN menus m ON pm.id_menu = m.id_menu WHERE pm.id_pedido = ?",
            "SELECT h.nombre, h.precio FROM pedido_hamburguesa ph JOIN hamburguesas h ON ph.id_hamburguesa = h.id_hamburguesa WHERE ph.id_pedido = ?",
            "SELECT b.nombre, b.precio FROM pedido_bebida pb JOIN bebidas b ON pb.id_bebida = b.id_bebida WHERE pb.id_pedido = ?",
            "SELECT c.nombre, c.precio FROM pedido_complemento pc JOIN complementos c ON pc.id_complemento = c.id_complemento WHERE pc.id_pedido = ?"
        ];

        foreach ($queries as $query) {
            $stmt = $con->prepare($query);
            $stmt->bind_param('i', $id_pedido);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
            $stmt->close();
        }

        $con->close();
        return $productos;
    }

    public static function getLatestPedidoByUsuarioId($id_usuario) { //Obtener el último pedido de un usuario basandonos en su id
        $con = DataBase::connect();
        $query = "SELECT * FROM pedidos WHERE id_usuario = ? ORDER BY fecha DESC LIMIT 1"; //Ordenar por fecha descendente y limitar a 1
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $pedido = $result->fetch_assoc();
        $stmt->close();
        $con->close();
        return $pedido;
    }
}
?>