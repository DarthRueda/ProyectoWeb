<!-- Migas de pan -->
<nav aria-label="breadcrumb" class="breadcrumb-container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Carta</li>
    </ol>
</nav>
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
        <div class="row justify-content-center mb-4">
            <div class="col-auto">
                <button class="btn-filter" onclick="filterProducts('all')">Todos</button>
                <button class="btn-filter" onclick="filterProducts('menus')">Menus</button>
                <button class="btn-filter" onclick="filterProducts('hamburguesa')">Hamburguesas</button>
                <button class="btn-filter" onclick="filterProducts('bebida')">Bebidas</button>
                <button class="btn-filter" onclick="filterProducts('complemento')">Complementos</button>
            </div>
        </div>
        <div class="row justify-content-center">
            <?php
            include_once 'models/productosDAO.php';
            $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
            switch ($filter) {
                case 'menus':
                    $productos = productosDAO::getMenus();
                    break;
                case 'hamburguesa':
                    $productos = productosDAO::getHamburguesas();
                    break;
                case 'bebida':
                    $productos = productosDAO::getBebidas();
                    break;
                case 'complemento':
                    $productos = productosDAO::getComplementos();
                    break;
                default:
                    $productos = productosDAO::getAll();
                    break;
            }
            // Paginación de productos
            $productosPorPagina = 9; // Número de productos por página
            $totalProductos = count($productos);
            $totalPaginas = ceil($totalProductos / $productosPorPagina); // Número total de páginas

            //Comprobamos si se ha pasado un número de página
            $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            $inicio = ($paginaActual - 1) * $productosPorPagina; // Índice del primer producto de la página
            $productosPagina = array_slice($productos, $inicio, $productosPorPagina); // Productos de la página actual

            //Variable para guardar el menu Russell y mostrarlo en la primera posición, ya que es el producto de oferta
            $menuRussell = null;
            foreach ($productosPagina as $key => $producto) { // Recorremos los productos de la página actual
                if ($producto->getNombre() == 'Menu Russell') { 
                    $menuRussell = $producto;
                    unset($productosPagina[$key]);
                    array_unshift($productosPagina, $menuRussell);
                    break;
                }
            }

            $count = 0;
            foreach ($productosPagina as $producto):
                if ($count % 3 == 0 && $count != 0): ?> <!-- Si el contador es múltiplo de 3 y no es 0, cerramos la fila actual y creamos una nueva -->
                    </div><div class="row justify-content-center">
                <?php endif; ?>
                <div class="col-md-4 mb-4 d-flex justify-content-center">
                    <div class="card" style="width: 18rem;">
                        <div class="img-container" style="<?= $producto->getNombre() == 'Menu Russell' ? 'background-color: #00A19C;' : '' ?>">
                            <img src="<?= $producto->getImagen() ?>" class="card-img-top" alt="<?= ucfirst($producto->getTipo()) ?> <?= $producto->getId() ?>">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-left"><?= $producto->getNombre() ?></h5>
                            <p class="card-text"><?= $producto->getDescripcion() ?></p>
                            <div class="d-flex align-items-center">
                                <form method="post" action="?controller=producto&action=añadirCarrito" class="add-to-cart-form">
                                    <input type="hidden" name="id" value="<?= $producto->getId() ?>">
                                    <input type="hidden" name="nombre" value="<?= $producto->getNombre() ?>">
                                    <input type="hidden" name="descripcion" value="<?= $producto->getDescripcion() ?>">
                                    <input type="hidden" name="precio" value="<?= $producto->getPrecio() ?>">
                                    <input type="hidden" name="imagen" value="<?= $producto->getImagen() ?>">
                                    <input type="hidden" name="tipo" value="<?= $producto->getTipo() ?>">
                                    <?php if ($producto->getTipo() == 'menus'): ?>
                                        <button type="button" class="btn-add" onclick="window.location.href='?controller=producto&action=modificar&id=<?= $producto->getId() ?>'">Añadir</button>
                                    <?php else: ?>
                                        <button type="button" class="btn-add" onclick="addToCart(this.form)">Añadir</button>
                                    <?php endif; ?>
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
                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <li class="page-item <?= $i == $paginaActual ? 'active' : '' ?>">
                                <a class="page-link" href="?controller=producto&action=carta&pagina=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>



<script>
// Función para añadir un producto al carrito
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

// Función para mostrar la caja de información
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

// Función para actualizar la cantidad de productos en el carrito
function actualizarCantidad(id, tipo, action) {
    fetch(`?controller=producto&action=actualizarCantidad`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id, tipo, action })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function filterProducts(type) {
    window.location.href = `?controller=producto&action=carta&filter=${type}`;
}
</script>