<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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

    <!-- Banner -->
    <section id="banner">
    <div class="container-fluid">
        <div class="row">
            <div class="col-8">
                <h1>FAST FORMULA, LA COMIDA MAS RÁPIDA</h1>
                <button class="btn-pedir">¡Pide ya!</button>
            </div>
            <div class="col-4">
                <div class="row flex-row h-100">
                    <div id="contador" class="col-12 text-center flex-fill d-flex flex-column justify-content-center">
                        <h2>OFERTA LIMITADA GEORGE RUSSELL</h2>
                        <p>¡Disfruta de un menú diseñado por el mismísimo George Russell!</p>
                    </div>
                    <div id="oferta" class="col-12 text-center flex-fill d-flex flex-column justify-content-center">
                        <img src="img/promoRussell.png" alt="promoRussell" class="img-fluid">
                        <button class="btn-oferta">¡Aprovecha la oferta!</button>    
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
            <img class="img-fluid w-100" src="img/parrilla.jpg" alt="parrilla">
        </div>
    </div>
</section>


    <!-- Menus -->
    <section id="menusGeneral">
        <div class="row">
            <div class="col-12">
                <h2>MENÚS MAS VENDIDOS</h2>
                <div id="menus" class="d-flex justify-content-around flex-wrap">
                    <div class="card" style="width: 18rem;">
                        <div class="img-container">
                            <img src="img/menu_alonso.png" class="card-img-top" alt="Menu Alonso">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Menu Alonso</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <a href="#" class="btn-add">Añadir</a>
                            <img src="img/flecha_roja.png" class="flecha-roja" alt="flecha_roja">
                        </div>
                    </div>
                    <div class="card" style="width: 18rem;">
                        <div class="img-container">
                            <img src="img/menu_sainz.png" class="card-img-top" alt="Menu Sainz">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Menu Sainz</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <a href="#" class="btn-add">Añadir</a>
                            <img src="img/flecha_roja.png" class="flecha-roja" alt="flecha_roja">
                        </div>
                    </div>
                    <div class="card" style="width: 18rem;">
                        <div class="img-container">
                            <img src="img/menu_perez.png" class="card-img-top" alt="Menu Perez">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Menu Perez</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <a href="#" class="btn-add">Añadir</a>
                            <img src="img/flecha_roja.png" class="flecha-roja" alt="flecha_roja">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <button class="btn-menus">Todos los menus</button>
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
                    <img src="img/flecha_blanca.png" alt="Enviar" class="img-enviar">
                </button>
            </form>
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