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
    <title>FAST FORMULA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="views/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;700&display=swap" rel="stylesheet">
    <!-- <link rel="icon" href="img/logo.svg" type="image/x-icon"> -->

</head>
<header>
    <div id="header">
            <div class="row">
                <div class="col-4">
                    <img src="views/img/logodef.svg" alt="logo" style="background-color: #E61414">
                </div>
                <div class="col-4">
                    <nav>
                        <ul>
                            <li><a href="?controller=producto&action=index">Inicio</a></li>
                            <li><a href="?controller=producto&action=carta">Carta</a></li>
                            <li><a href="?controller=producto&action=novedades">Novedades</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- Botones Interactivos -->
                <div class="col-4">
                    <a href="?controller=producto&action=carrito"><img src="views/img/carrito.svg" class="img-fluid" alt="logo_carrito"></a>
                    <!-- Si el usuario está logueado, mostrará el botón de usuario y cerrar sesión, si no, mostrará el botón de usuario -->
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                        <a href="?controller=usuario&action=menu_usuario"><img src="views/img/usuario.svg" class="img-fluid" alt="logo_usuario"></a>
                        <a href="?controller=usuario&action=cerrar_sesion"><img src="views/img/cerrar-sesion.svg" class="img-fluid" alt="logo_cerrar_sesion"></a>
                    <?php else: ?>
                        <a href="?controller=usuario&action=login"><img src="views/img/usuario.svg" class="img-fluid" alt="logo_usuario"></a>
                    <?php endif; ?>
                </div>
            </div>
    </div>
</header>
<body>
    <?php 
    // Ensure that the $data array is available in the view
    include_once $view; 
    ?>
</body>
<!-- Footer -->
<footer>
        <div class="row">
            <div class="col-5 d-flex justify-content-end align-items-center footer-red">
            <img src="views/img/logofull.png" alt="logo" class="footer-logo img-fluid">
            </div>
            <div class="col-7 footer-black">
            <img src="views/img/logof1.png" alt="logof1" class="img-fluid">
            <img src="views/img/logocerveza.svg" alt="logocerveza" class="img-fluid">
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
                    <img src="views/img/facebook.svg" alt="facebook">
                    <img src="views/img/twitter.svg" alt="twitter">
                    <img src="views/img/youtube.svg" alt="youtube">
                    <img src="views/img/instagram.svg" alt="instagram">
                    <img src="views/img/flikr.svg" alt="flikr">
                    <img src="views/img/linkedin.svg" alt="linkedin">
                    <img src="views/img/whatsapp.svg" alt="whatsapp">
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
</html>