<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) { //Si no está logueado lo redirige a la página de login
    header("Location: index.php?controller=usuario&action=login");
    exit;
}

include_once("models/pedidosDAO.php");
$pedidos = pedidosDAO::getPedidosByUsuarioId($_SESSION['id_usuario']); //Obtiene los pedidos del usuario logueado
?>


<!-- Mostremos los pedidos del usuario logueado  Sujeto a CAMBIOS-->
<div class="pedidos-info" style="max-width: 800px; margin: 0 auto;">
    <h2>Información de Pedidos</h2>
    <?php if (!empty($pedidos)): ?>
        <ul>
            <?php foreach ($pedidos as $pedido): ?>
                <li>
                    <strong>Pedido ID:</strong> <?= $pedido['id_pedido'] ?> - <strong>Total:</strong> <?= $pedido['total'] ?>€ 
                    <ul>
                        <?php
                        $productos = pedidosDAO::getProductosByPedidoId($pedido['id_pedido']);
                        foreach ($productos as $producto):
                        ?>
                            <li><?= $producto['nombre'] ?> - <?= $producto['precio'] ?>€</li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No tienes pedidos.</p>
    <?php endif; ?>
</div>
