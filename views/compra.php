<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<body>
    <section id="banner-compra">
        <div class="row">
            <div class="col-6">
                <img src="views/img/local3.jpg" alt="Logo" class="img-local">
            </div>
            <div class="col-6">
                <h1>TU PEDIDO ESTA CASI LISTO</h1>
            </div>
        </div>
    </section>
    <section id="resumen-compra">
        <div class="row">
            <div class="col-6 datos">
                <!-- Formulario -->
                <h1>DATOS DE ENTREGA</h1>
                <form id="form-datos-entrega" action="?controller=producto&action=exito" method="post">
                    <input type="hidden" name="id_pedido" value="<?= $_SESSION['id_pedido'] ?? '' ?>">
                    <div class="form-group">
                        <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre" value="<?= $_SESSION['nombre'] ?? '' ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="email" id="correo" name="correo" class="form-control" placeholder="Correo" value="<?= $_SESSION['email'] ?? '' ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" id="telefono" name="telefono" class="form-control" placeholder="Teléfono" value="<?= $_SESSION['telefono'] ?? '' ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="direccion" name="direccion" class="form-control" placeholder="Dirección" value="<?= $_SESSION['direccion'] ?? '' ?>" required>
                    </div>
                </form>

                <!-- Metodos de entrega -->
                <h1>MÉTODO DE ENTREGA</h1>
                <div class="metodos-entrega">
                    <img src="views/img/uber.jpg" alt="Imagen Uber" width="142" height="122" onclick="seleccionarMetodoEntrega(this)">
                    <img src="views/img/glovo.jpg" alt="Imagen Glovo" width="142" height="122" onclick="seleccionarMetodoEntrega(this)">
                    <img src="views/img/justeat.jpg" alt="Imagen Just Eat" width="142" height="122" onclick="seleccionarMetodoEntrega(this)">
                </div>

                <!-- Metodos de pago -->
                <h1>MÉTODO DE PAGO</h1>
                <div class="metodos-pago">
                    <label>
                        <input type="radio" name="metodo_pago" value="bizum" required onclick="mostrarDetallesPago('bizum')">
                        Bizum
                        <img src="views/img/logobizum.png" alt="Bizum">
                    </label>
                    <div id="bizum-detalles" class="detalles-pago">
                        Número Bizum: 6552
                    </div>
                    <label>
                        <input type="radio" name="metodo_pago" value="tarjeta_credito" required onclick="mostrarDetallesPago('tarjeta_credito')">
                        Tarjeta de Crédito
                        <img src="views/img/logotarjeta.png" alt="Tarjeta de Crédito">
                    </label>
                    <div id="tarjeta-detalles" class="detalles-pago">
                        <input type="text" id="card-number" name="card_number" placeholder="Número de la tarjeta" required>
                        <input type="text" id="card-expiry" name="card_expiry" placeholder="Fecha de caducidad" required>
                        <input type="text" id="card-cvc" name="card_cvc" placeholder="CVC" required>
                    </div>
                </div>
                
            </div>
            <div class="col-6" id="resumen" style="height: 50%;">
                <h3>RESUMEN DE COMPRA</h3>
                <?php if (isset($_SESSION['cart_data']) && !empty($_SESSION['cart_data'])): ?>
                    <ul>
                        <?php foreach ($_SESSION['cart_data'] as $producto): ?>
                            <li><?= $producto['nombre'] ?> - <?= $producto['precio'] ?>€</li>
                        <?php endforeach; ?>
                    </ul>
                    <?php
                    if (isset($_SESSION['id_pedido'])) {
                        $pedido = number_format(pedidosDAO::getPedidoByPedidoId($_SESSION['id_pedido']), 2);
                        $iva = number_format(pedidosDAO::getIvaByPedidoId($_SESSION['id_pedido']), 2);
                        $total = number_format(pedidosDAO::getTotalByPedidoId($_SESSION['id_pedido']), 2);
                        $descuento = pedidosDAO::getDescuentoByPedidoId($_SESSION['id_pedido']);
                        echo "<p>Pedido: $pedido €</p>";
                        echo "<p class='iva'>IVA: $iva €</p>";
                        if ($descuento > 0) {
                            echo "<p>Código Promocional: -$descuento%</p>";
                        }
                        echo "<div class='divider'></div>";
                        echo "<p class='total'><strong>TOTAL:</strong> $total €</p>";
                        echo "<button class='btn-pagar' onclick='validarFormulario()'>PAGAR</button>";
                    } else {
                        echo "<p>No se encontró el ID del pedido.</p>"; //Pruebas
                    }
                    ?>
                <?php else: ?>
                    <p>No hay productos en el carrito.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <div class="boton-pagar">
        <button class="btn-pagar2" onclick="validarFormulario()">PAGAR</button>
    </div>
    <script>
        // TODAS ESTAS FUNCIONES ESTAN SUJETAS A CAMBIOS PARA LA POSSIBILIDAD DE HACERLAS EN PHP
        function seleccionarMetodoEntrega(element) {
            const methods = document.querySelectorAll('.metodos-entrega img');
            methods.forEach(method => method.classList.remove('selected'));
            element.classList.add('selected');
        }

        function mostrarDetallesPago(method) {
            document.getElementById('bizum-detalles').style.display = method === 'bizum' ? 'block' : 'none';
            document.getElementById('tarjeta-detalles').style.display = method === 'tarjeta_credito' ? 'block' : 'none';
        }

        function validarFormulario() {
            const selectedPaymentMethod = document.querySelector('input[name="metodo_pago"]:checked');
            const selectedDeliveryMethod = document.querySelector('.metodos-entrega img.selected');
            const formDatosEntrega = document.getElementById('form-datos-entrega');
            const cardNumber = document.getElementById('card-number');
            const cardExpiry = document.getElementById('card-expiry');
            const cardCvc = document.getElementById('card-cvc');
            
            if (!selectedPaymentMethod) {
                alert('Por favor, seleccione un método de pago.');
                return false;
            }
            if (!selectedDeliveryMethod) {
                alert('Por favor, seleccione un método de entrega.');
                return false;
            }
            if (!formDatosEntrega.checkValidity()) {
                alert('Por favor, complete todos los campos de datos de entrega.');
                return false;
            }
            if (selectedPaymentMethod.value === 'tarjeta_credito' && (!cardNumber.value || !cardExpiry.value || !cardCvc.value)) {
                alert('Por favor, complete todos los campos de la tarjeta de crédito.');
                return false;
            }
            formDatosEntrega.submit();
        }
    </script>
</body>