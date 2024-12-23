<!DOCTYPE html>
<html lang="es">
<head>
    <link href="https://fonts.cdnfonts.com/css/ds-digital" rel="stylesheet"> <!-- Añadimos la fuente DS-Digital -->
</head>
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
                        <?php echo (new productoController())->getCountdownScript(); ?>
                    </div>
                    <div id="oferta" class="col-12 text-center flex-fill d-flex flex-column justify-content-center">
                        <img src="views/img/promoRussell.svg" alt="promoRussell" class="img-fluid">
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
            <img class="img-fluid w-100" src="views/img/parrilla.webp" alt="parrilla">
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
                        return $producto->getTipo() == 'menus' && $producto->getId() >= 1 && $producto->getId() <= 3;
                    });
                    foreach ($menus as $menu) {
                    ?>
                    <div class="card" style="width: 18rem;">
                        <div class="img-container">
                            <img src="<?php echo $menu->getImagen(); ?>" class="card-img-top" alt="<?php echo $menu->getNombre(); ?>">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $menu->getNombre(); ?></h5>
                            <p class="card-text"><?php echo $menu->getDescripcion(); ?></p>
                            <div class="d-flex align-items-center">
                                <form method="post" action="?controller=producto&action=modificar&id=<?php echo $menu->getId(); ?>">
                                    <input type="hidden" name="id" value="<?php echo $menu->getId(); ?>">
                                    <input type="hidden" name="nombre" value="<?php echo $menu->getNombre(); ?>">
                                    <input type="hidden" name="descripcion" value="<?php echo $menu->getDescripcion(); ?>">
                                    <input type="hidden" name="precio" value="<?php echo $menu->getPrecio(); ?>">
                                    <input type="hidden" name="imagen" value="<?php echo $menu->getImagen(); ?>">
                                    <button type="submit" class="btn-add">Añadir</button>
                                </form>
                                <img src="views/img/flecha_roja.svg" class="flecha-roja" alt="flecha_roja">
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
            <h2><b>UNETE A NOSOTROS Y DISFRUTA DE TODAS NUESTRAS VENTAJAS</b></h2>
            <form class="container-fluid d-flex align-items-center" method="POST" action="?controller=usuario&action=rediriguirRegistro">
                <input type="text" name="nombre" placeholder="Nombre">
                <input type="text" name="apellido" placeholder="Apellido">
                <input type="email" name="email" placeholder="Correo">
                <button class="btn-enviar">
                    <img src="views/img/flecha_blanca.svg" alt="Enviar" class="img-enviar">
                </button>
            </form>
        </div>
    </section>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>