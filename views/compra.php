<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAST FORMULA | Compra</title>
</head>
<nav aria-label="breadcrumb" class="breadcrumb-container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="carrito.php">Carrito</a></li>
        <li class="breadcrumb-item active" aria-current="page">Compra</li>
    </ol>
</nav>
<body>
    <section id="banner-compra">
        <div class="row">
            <div class="col-6">
                <img src="views/img/local3.webp" alt="Logo" class="img-local">
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
                    <img src="views/img/uber.webp" alt="Imagen Uber" width="142" height="122" onclick="seleccionarMetodoEntrega(this)">
                    <img src="views/img/glovo.webp" alt="Imagen Glovo" width="142" height="122" onclick="seleccionarMetodoEntrega(this)">
                    <img src="views/img/justeat.webp" alt="Imagen Just Eat" width="142" height="122" onclick="seleccionarMetodoEntrega(this)">
                </div>

                <!-- Metodos de pago -->
                <h1>MÉTODO DE PAGO</h1>
                <div class="metodos-pago">
                    <label>
                        <input type="radio" name="metodo_pago" value="bizum" required onclick="mostrarDetallesPago('bizum')">
                        Bizum
                        <img src="views/img/logobizum.svg" alt="Bizum">
                    </label>
                    <div id="bizum-detalles" class="detalles-pago">
                        Número Bizum: 6552
                    </div>
                    <label>
                        <input type="radio" name="metodo_pago" value="tarjeta_credito" required onclick="mostrarDetallesPago('tarjeta_credito')">
                        Tarjeta de Crédito
                        <img src="views/img/logotarjeta.svg" alt="Tarjeta de Crédito">
                    </label>
                    <div id="tarjeta-detalles" class="detalles-pago">
                        <input type="text" id="card-number" name="card_number" placeholder="Número de la tarjeta" required>
                        <input type="text" id="card-expiry" name="card_expiry" placeholder="Fecha de caducidad" required>
                        <input type="text" id="card-cvc" name="card_cvc" placeholder="CVC" required>
                    </div>
                    <label>
                        <input type="radio" name="metodo_pago" value="paypal" required onclick="mostrarDetallesPago('paypal')">
                        PayPal
                        <img src="views/img/paypal.svg" alt="PayPal">
                    </label>
                    <div id="paypal-detalles" class="detalles-pago">
                        <input type="text" id="paypal-country" name="paypal_country" placeholder="País" required>
                        <input type="text" id="paypal-name" name="paypal_name" placeholder="Nombre" required>
                        <input type="text" id="paypal-apellido" name="paypal_apellido" placeholder="Apellido" required>
                        <input type="text" id="paypal-amount" name="paypal_amount" placeholder="Cantidad a pagar" value="<?= number_format($data['total'], 2) ?>" readonly required>
                    </div>
                </div>
                
            </div>
            <div class="col-6" id="resumen" style="height: 50%;">
                <h3>RESUMEN DE COMPRA</h3>
                <?php if (!empty($data['cart_data'])): ?>
                    <ul>
                        <?php foreach ($data['cart_data'] as $producto): ?>
                            <li><?= $producto['nombre'] ?> - <?= $producto['precio'] ?>€ x <?= $producto['cantidad'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php
                    if (isset($data['id_pedido'])) {
                        echo "<p>Pedido: " . number_format($data['pedido'], 2) . " €</p>";
                        echo "<p class='iva'>IVA: " . number_format($data['iva'], 2) . " €</p>";
                        if ($data['descuento'] > 0) {
                            echo "<p>Código Promocional: -" . $data['descuento'] . "%</p>";
                        }
                        echo "<div class='divider'></div>";
                        echo "<p class='total'><strong>TOTAL:</strong> " . number_format($data['total'], 2) . " €</p>";
                        echo "<button class='btn-pagar' onclick='validarFormulario()'>PAGAR</button>";
                    } else {
                        echo "<p>No se encontró el ID del pedido.</p>";
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
        //Funcion para añadir seleccionar un método de entrega
        function seleccionarMetodoEntrega(element) {
            const methods = document.querySelectorAll('.metodos-entrega img');
            methods.forEach(method => method.classList.remove('selected'));
            element.classList.add('selected');
        }
        //Funcion para mostrar los detalles de pago
        function mostrarDetallesPago(method) {
            document.getElementById('bizum-detalles').style.display = method === 'bizum' ? 'block' : 'none';
            document.getElementById('tarjeta-detalles').style.display = method === 'tarjeta_credito' ? 'block' : 'none';
            document.getElementById('paypal-detalles').style.display = method === 'paypal' ? 'block' : 'none';
        }

        //Funcion para validar el formulario
        function validarFormulario() {
            const selectedPaymentMethod = document.querySelector('input[name="metodo_pago"]:checked');
            const selectedDeliveryMethod = document.querySelector('.metodos-entrega img.selected');
            const formDatosEntrega = document.getElementById('form-datos-entrega');
            const cardNumber = document.getElementById('card-number');
            const cardExpiry = document.getElementById('card-expiry');
            const cardCvc = document.getElementById('card-cvc');
            const paypalCountry = document.getElementById('paypal-country');
            const paypalName = document.getElementById('paypal-name');
            const paypalApellido = document.getElementById('paypal-apellido');
            
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
            if (selectedPaymentMethod.value === 'paypal' && (!paypalCountry.value || !paypalName.value || !paypalApellido.value)) {
                alert('Por favor, complete todos los campos de PayPal.');
                return false;
            }
            formDatosEntrega.submit();
        }
    </script>
</body>
</html>