<body>
    <!-- Breadcrumbs Navigation -->
    <nav aria-label="breadcrumb" class="breadcrumb-container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Carrito</li>
        </ol>
    </nav>

    <!-- Bloque 1 -->
    <section id="intro-carrito">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-left">
                    <h1>CARRITO</h1>
                    <p>
                    Desde esta pagina podras ver todos los productos que tienes actualmente en tu carrito.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Bloque 2 -->
    <section id="bloque2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex flex-column align-items-center">
                    <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                        <?php $_SESSION['cart_data'] = $_SESSION['cart']; ?>
                        <?php foreach ($_SESSION['cart'] as $producto): ?>
                            <div class="producto">
                                <div class="img-container">
                                    <img src="<?= $producto['imagen'] ?>" alt="<?= $producto['nombre'] ?>">
                                </div>
                                <div class="info">
                                    <h3><?= $producto['nombre'] ?></h3>
                                    <p><?= $producto['descripcion'] ?></p>
                                </div>
                                <div class="price-bin">
                                    <span class="price"><?= $producto['precio'] ?>€</span>
                                    <!-- Formularios para aumentar y disminuir la cantidad de productos -->
                                    <div class="quantity-container">
                                        <form method="post" action="?controller=producto&action=actualizarCantidad">
                                            <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                                            <input type="hidden" name="tipo" value="<?= $producto['tipo'] ?>">
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit"><img src="views/img/menos.png" alt="Menos"></button>
                                        </form>
                                        <input type="text" value="<?= $producto['cantidad'] ?>" readonly>
                                        <form method="post" action="?controller=producto&action=actualizarCantidad">
                                            <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                                            <input type="hidden" name="tipo" value="<?= $producto['tipo'] ?>">
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit"><img src="views/img/mas.png" alt="Más"></button>
                                        </form>
                                    </div>
                                    <form method="post" action="?controller=producto&action=eliminarCarrito">
                                        <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                                        <input type="hidden" name="tipo" value="<?= $producto['tipo'] ?>">
                                        <button type="submit"><img src="views/img/papelera.png" alt="Eliminar"></button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <form method="post" action="?controller=producto&action=tramitarPedido">
                            <input type="text" id="codigo_promocional" name="codigo_promocional" class="form-control" placeholder="Código Promocional">
                            <button type="submit" class="btn-tramitar">Tramitar Pedido</button>
                        </form>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $_SESSION['id_pedido'] = pedidosDAO::guardarPedido($_SESSION['cart']); // Guardamos el id del pedido en la sesion
                            header("Location: compra.php"); // Redirigimos al usuario a la pagina de compra
                            exit();
                        }
                        ?>
                    <?php else: ?>
                        <p>No hay productos en el carrito.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>