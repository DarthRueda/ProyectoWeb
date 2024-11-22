<?php
include_once 'config/dataBase.php';

class pedidosDAO {
    public static function guardarPedido($productos) {
        $con = DataBase::connect();
        
        // Insertar valores en la base de datos
        $query = "INSERT INTO pedidos (total, id_oferta) VALUES (0, ?)";
        $stmt = $con->prepare($query);
        $id_oferta = null; // No hay ofertas implementadas por defecto
        $stmt->bind_param('i', $id_oferta);
        $stmt->execute();
        $id_pedido = $stmt->insert_id;
        $stmt->close();

        // Calcular el total del pedido
        $total = 0;
        foreach ($productos as $producto) {
            $total += $producto['precio'];
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

        // Actualizar el total del pedido
        $query = "UPDATE pedidos SET total = ? WHERE id_pedido = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('di', $total, $id_pedido);
        $stmt->execute();
        $stmt->close();

        $con->close();
    }
}
?>