<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <!-- Header -->
    <header>
        <div id="header">
            <div class = "container-fluid">
                <div class="row">
                    <div class="col-4">
                        <img src="../views/img/logofull.png" alt="logo"> <!-- Insertar logo -->
                    </div>
                    <div class="col-4">
                        <nav>
                            <ul>
                                <li><a href="home.php">Inicio</a></li>
                                <li><a href="carta.php">Carta</a></li>
                                <li><a href="novedades.php">Novedades</a></li>
                            </ul>
                        </nav>
                    </div>
                    <!-- Botones Interactivos -->
                    <div class="col-4">
                        <img src="img/carrito.png" class="img-fluid" href= "" alt="logo_carrito">
                        <img src="img/usuario.png" class="img-fluid" href="" alt="logo_usuario">
                    </div>
                </div>
            </div>
        </div>
    </header>

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
                <?php for ($i = 1; $i <= 15; $i++): ?>
                    <div class="col-md-4 mb-4 d-flex justify-content-center">
                        <div class="card" style="width: 18rem;">
                            <div class="img-container">
                                <img src="img/burger<?= $i ?>.png" class="card-img-top" alt="Burger <?= $i ?>">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Burger <?= $i ?></h5>
                                <p class="card-text">Description for Burger <?= $i ?>.</p>
                                <a href="#" class="btn-add">Añadir</a>
                                <img src="img/flecha_roja.png" class="flecha-roja" alt="flecha_roja">
                            </div>
                        </div>
                    </div>
                    <?php if ($i % 3 == 0): ?>
                        </div><div class="row justify-content-center">
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        </div>
        <div class="container my-4">
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

    

    <!-- Footer -->
    <footer>
        <div id="primer-footer" class="container-fluid">
            <div class="row">
                <div class="col-5 d-flex justify-content-end align-items-center">
                    <img src="img/logofull.png" alt="logo" class="footer-logo">
                </div>
                <div class="col-7">
                    <img src="img/logof1.png" alt="logof1">
                    <img src="img/logocerveza.png" alt="logocerveza">
                </div>
            </div>
        </div>
        <hr>
        <div>
            <div class="row text-center">
                <div class="col-12 d-flex justify-content-center">
                    <div class="footer-links">
                        <p><b>Terminos y condiciones</b></p>
                        <p><b>Politica de privacidad</b></p>
                    </div>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-12 text-left">
                    <p class="footer-left"><b>Trabaja con nosotros</b></p>
                    <div class="col-12 d-flex justify-content-end footer-right">
                        <img src="img/facebook.png" alt="facebook">
                        <img src="img/twitter.png" alt="twitter">
                        <img src="img/youtube.png" alt="youtube">
                        <img src="img/instagram.png" alt="instagram">
                        <img src="img/flikr.png" alt="flikr">
                        <img src="img/linkedin.png" alt="linkedin">
                        <img src="img/whatsapp.png" alt="whatsapp">
                    </div>
                </div>
            </div>
            <div class="row align-items-center">

                <div class="col-12 d-flex justify-content-center">
                    <p class="footer-center"><b>© 2021 Fast Formula</b></p>
                </div>
            </div>
        </div>
    </footer>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>