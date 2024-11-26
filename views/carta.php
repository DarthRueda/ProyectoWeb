<!-- Bloque 1 -->
<section id="intro-carta">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-left">
                <h1>DISFRUTA DE NUESTRA EXTENSA CARTA</h1>
                <p>
                    Desde la parrila hasta el pollo pasando por nuestras hamburguesas veganas. 
                    Escoge entre una variedad de menus inspirados en tus pilotos favoritos, o si lo 
                    prefieres selecciona una hambueruesa individual y acompañala con una bebida
                </p>
            </div>
        </div>
    </div>
</section>

<section id="carta" style="background-color: #F3F3F3;">
    <div class="container">
        <div class="row justify-content-center">
            <?php
            include_once 'models/productosDAO.php';
            $productos = productosDAO::getAll();
            $count = 0;
            foreach ($productos as $producto):
                if ($count % 3 == 0 && $count != 0): ?>
                    </div><div class="row justify-content-center">
                <?php endif; ?>
                <div class="col-md-4 mb-4 d-flex justify-content-center">
                    <div class="card" style="width: 18rem;">
                        <div class="img-container">
                            <img src="<?= $producto['imagen'] ?>" class="card-img-top" alt="<?= ucfirst($producto['tipo']) ?> <?= $producto['id'] ?>">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-left"><?= $producto['nombre'] ?></h5>
                            <p class="card-text"><?= $producto['descripcion'] ?></p>
                            <div class="d-flex align-items-center">
                                <form method="post" action="?controller=producto&action=añadirCarrito" class="add-to-cart-form">
                                    <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                                    <input type="hidden" name="nombre" value="<?= $producto['nombre'] ?>">
                                    <input type="hidden" name="descripcion" value="<?= $producto['descripcion'] ?>">
                                    <input type="hidden" name="precio" value="<?= $producto['precio'] ?>">
                                    <input type="hidden" name="imagen" value="<?= $producto['imagen'] ?>">
                                    <button type="button" class="btn-add" onclick="addToCart(this.form)">Añadir</button>
                                </form>
                                <img src="views/img/flecha_roja.png" class="flecha-roja" alt="flecha_roja">
                            </div>
                        </div>
                    </div>
                </div>
                <?php $count++;
            endforeach;
            while ($count % 3 != 0): ?>
                <div class="col-md-4 mb-4 d-flex justify-content-center"></div>
                <?php $count++;
            endwhile; ?>
        </div>
    </div>
    <div class="numero-pagina">
        <div class="row justify-content-center">
            <div class="col-auto">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>


<!-- Script que sirve para generar la caja de informacion cuando el producto ha sido añadido -->
<script>
function addToCart(form) {
    const formData = new FormData(form);
    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showInfoBox(data.product);
        }
    });
}

function showInfoBox(product) {
    const infoBox = document.createElement('div');
    infoBox.className = 'info-box';
    infoBox.innerHTML = `
        <img src="${product.imagen}" alt="${product.nombre}">
        <p>${product.nombre} añadido al carrito</p>
    `;
    document.body.appendChild(infoBox);
    setTimeout(() => {
        infoBox.remove();
    }, 3000);
}
</script>