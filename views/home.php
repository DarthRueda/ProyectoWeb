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
                <button>¡Pide ya!</button>
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h2>MENÚS MAS VENDIDOS</h2>
                    <div id="menus">
                        <div class="card" style="width: 18rem;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                    </div>
                </div>
            </div>
            <button>Todos los menus</button>
        </div>
    </section>

    <!-- Form -->
    <section>
        <div class="container-fluid">
            <h2>UNETE A NOSOTROS Y DISFRUTA DE TODAS NUESTRAS VENTAJAS</h2>
            <form>
                <input type="text" placeholder="Nombre">
                <input type="text" placeholder="Apellido">
                <input type="email" placeholder="Correo">
                <button>Enviar</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container-fluid">
            <div class="col-3">
                <h3>FAST FORMULA</h3>
                <p>La comida más rápida</p>
            </div>
            <div class="col-9">
                <img src="img/logo.png" alt="logo"> <!-- Insertar patrocinador 1 -->
                <img src="img/logo.png" alt="logo"> <!-- Insertar patrocinador 2 -->
            </div>
        </div>
    </footer>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>