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
            include_once 'models/hamburguesaDAO.php';
            $hamburguesas = hamburguesaDAO::getAll();
            foreach ($hamburguesas as $hamburguesa):
            ?>
                <div class="col-md-4 mb-4 d-flex justify-content-center">
                    <div class="card" style="width: 18rem;">
                        <div class="img-container">
                            <img src="views/img/burger<?= $hamburguesa->getId() ?>.png" class="card-img-top" alt="Burger <?= $hamburguesa->getId() ?>">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-left"><?= $hamburguesa->getNombre() ?></h5>
                            <p class="card-text"><?= $hamburguesa->getDescripcion() ?></p>
                            <a href="#" class="btn-add">Añadir</a>
                            <img src="views/img/flecha_roja.png" class="flecha-roja" alt="flecha_roja">
                        </div>
                    </div>
                </div>
                <?php if ($hamburguesa->getId() % 3 == 0): ?>
                    </div><div class="row justify-content-center">
                <?php endif; ?>
            <?php endforeach; ?>
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