<body>
    <section id="banner">
    <div class="container-fluid">
        <div class="row">
            <div class="col-8">
                <h1>FAST FORMULA, LA COMIDA MAS RÁPIDA</h1>
                <button class="btn-pedir" onclick="location.href='?controller=producto&action=carta'">¡Pide ya!</button>
            </div>
            <div class="col-4">
                <div class="row flex-row h-100">
                    <div id="contador" class="col-12 text-center flex-fill d-flex flex-column justify-content-center">
                        <h2>OFERTA LIMITADA GEORGE RUSSELL</h2>
                        <p>¡Disfruta de un menú diseñado por el mismísimo George Russell!</p>
                    </div>
                    <div id="oferta" class="col-12 text-center flex-fill d-flex flex-column justify-content-center">
                        <img src="views/img/promoRussell.png" alt="promoRussell" class="img-fluid">
                        <button class="btn-oferta" onclick="location.href='?controller=producto&action=carta'">¡Aprovecha la oferta!</button>   
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Sección 1 -->
    <section id="seccion1">
    <div class="row">
        <div class="col-12 text-center flex-fill d-flex flex-column justify-content-center">
            <h2>Disfruta de las <b style="color: #E61414">hamburguesas</b> inspiradas en el mundo de la <b style="color: #E61414">F1</b></h2>
            <p>Nos dedicamos a la parilla desde 1983</p>
        </div>
        <div class="col-12 text-center flex-fill d-flex flex-column justify-content-center">
            <img class="img-fluid w-100" src="views/img/parrilla.jpg" alt="parrilla">
        </div>
    </div>
</section>


    <!-- Menus -->
    <section id="menusGeneral">
        <div class="row">
            <div class="col-12">
                <h2>MENÚS MAS VENDIDOS</h2>
                <div id="menus" class="d-flex justify-content-around flex-wrap">
                    <?php
                    include_once 'models/productosDAO.php';
                    $productos = productosDAO::getAll();
                    $menus = array_filter($productos, function($producto) {
                        return $producto['tipo'] == 'menus' && $producto['id'] >= 1 && $producto['id'] <= 3;
                    });
                    foreach ($menus as $menu) {
                    ?>
                    <div class="card" style="width: 18rem;">
                        <div class="img-container">
                            <img src="<?php echo $menu['imagen']; ?>" class="card-img-top" alt="<?php echo $menu['nombre']; ?>">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $menu['nombre']; ?></h5>
                            <p class="card-text"><?php echo $menu['descripcion']; ?></p>
                            <div class="d-flex align-items-center">
                                <form method="post" action="?controller=producto&action=añadirCarrito">
                                    <input type="hidden" name="id" value="<?php echo $menu['id']; ?>">
                                    <input type="hidden" name="nombre" value="<?php echo $menu['nombre']; ?>">
                                    <input type="hidden" name="descripcion" value="<?php echo $menu['descripcion']; ?>">
                                    <input type="hidden" name="precio" value="<?php echo $menu['precio']; ?>">
                                    <input type="hidden" name="imagen" value="<?php echo $menu['imagen']; ?>">
                                    <button type="submit" class="btn-add">Añadir</button>
                                </form>
                                <img src="views/img/flecha_roja.png" class="flecha-roja" alt="flecha_roja">
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <button class="btn-menus" onclick="location.href='?controller=producto&action=carta'">Todos los menus</button>
        </div>
    </section>

    <!-- Form -->
    <section id="form">
        <div class="form-container text-center">
            <h2>UNETE A NOSOTROS Y DISFRUTA DE TODAS NUESTRAS VENTAJAS</h2>
            <form class="container-fluid d-flex align-items-center">
                <input type="text" placeholder="Nombre">
                <input type="text" placeholder="Apellido">
                <input type="email" placeholder="Correo">
                <button class="btn-enviar">
                    <img src="views/img/flecha_blanca.png" alt="Enviar" class="img-enviar">
                </button>
            </form>
        </div>
    </section>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>