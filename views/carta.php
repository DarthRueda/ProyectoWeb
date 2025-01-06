<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAST FORMULA | Carta</title>
</head>
<body>
    <!-- Migas de pan -->
    <nav aria-label="breadcrumb" class="breadcrumb-container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">HOME</a></li>
            <li class="breadcrumb-item active" aria-current="page">CARTA</li>
        </ol>
    </nav>
    <!-- Bloque 1 -->
    <section id="intro-carta">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-left">
                    <h1>DISFRUTA DE NUESTRA EXTENSA CARTA</h1>
                    <p>
                        Desde la parrilla hasta el pollo pasando por nuestras hamburguesas veganas. 
                        Escoge entre una variedad de menús inspirados en tus pilotos favoritos, 
                        o si lo prefieres selecciona una hamburguesa individual y acompáñala con una bebida
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
                    <button class="btn-filter" onclick="filterProducts('menus')">Menús</button>
                    <button class="btn-filter" onclick="filterProducts('hamburguesa')">Hamburguesas</button>
                    <button class="btn-filter" onclick="filterProducts('bebida')">Bebidas</button>
                    <button class="btn-filter" onclick="filterProducts('complemento')">Complementos</button>
                </div>
            </div>
            <div class="row justify-content-center">
                <?php
                $productos = $data['productos'];
                $productosPagina = $data['productosPagina'];
                $menuRussell = $data['menuRussell'];
                $count = 0;
                foreach ($productosPagina as $producto):
                    if ($count % 3 == 0 && $count != 0): ?> <!-- Si el contador es múltiplo de 3 y no es 0, cerramos la fila actual y creamos una nueva -->
                        </div><div class="row justify-content-center">
                    <?php endif; ?>
                    <div class="col-md-4 mb-4 d-flex justify-content-center">
                        <div class="card" style="width: 18rem;">
                            <div class="img-container" style="<?= $producto->getNombre() == 'Menú Russell' ? 'background-color: #00A19C;' : '' ?>"> <!-- Si el producto es el menú Russell, le damos un color de fondo diferente -->
                                <img src="<?= $producto->getImagen() ?>" class="card-img-top" alt="<?= ucfirst($producto->getTipo()) ?> <?= $producto->getId() ?>">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-left"><?= $producto->getNombre() ?></h5>
                                <p class="card-text">
                                    <?= strlen($producto->getDescripcion()) > 50 ? substr($producto->getDescripcion(), 0, 50) . '...' : $producto->getDescripcion() ?> <!-- Si la descripción es mayor a 50 caracteres, mostramos los primeros 50 y añadimos puntos suspensivos -->
                                </p>
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
                                    <img src="views/img/flecha_roja.svg" class="flecha-roja" alt="flecha_roja">
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
                            <?php for ($i = 1; $i <= $data['totalPaginas']; $i++): ?>
                                <li class="page-item <?= $i == $data['paginaActual'] ? 'active' : '' ?>">
                                    <a class="page-link" href="?controller=producto&action=carta&pagina=<?= $i ?>&filter=<?= $data['filter'] ?>"><?= $i ?></a>
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
</body>
</html>