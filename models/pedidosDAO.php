<?php
include_once 'config/dataBase.php';

class pedidosDAO {
    public static function guardarPedido($productos, $codigo_promocional = null) {
        $con = DataBase::connect();
        
        // Insertar valores en la base de datos
        $query = "INSERT INTO pedidos (pedido, iva, total, id_oferta) VALUES (0, 0, 0, ?)";
        $stmt = $con->prepare($query);
        $id_oferta = null; // No hay ofertas implementadas por defecto
        $stmt->bind_param('i', $id_oferta);
        $stmt->execute();
        $id_pedido = $stmt->insert_id;
        $stmt->close();

        // Calcular el total del pedido
        $pedido = 0;
        foreach ($productos as $producto) {
            $pedido += $producto['precio'];
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

        // Calcular el IVA
        $iva = round($pedido * 0.10, 2);
        $total = $pedido + $iva;

        // Aplicar descuento si hay un código promocional válido
        $descuento = 0;
        if ($codigo_promocional) {
            $query = "SELECT id_oferta, descuento FROM ofertas WHERE nombre = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('s', $codigo_promocional);
            $stmt->execute();
            $stmt->bind_result($id_oferta, $descuento);
            if ($stmt->fetch()) {
                $stmt->close();
                $query = "UPDATE pedidos SET id_oferta = ? WHERE id_pedido = ?";
                $stmt = $con->prepare($query);
                $stmt->bind_param('ii', $id_oferta, $id_pedido);
                $stmt->execute();
                $stmt->close();
                $descuento = round($total * ($descuento / 100), 2); // Calculate discount as a percentage
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
}
?>