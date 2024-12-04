<?php
include_once 'models/productosDAO.php';
$menuId = $_GET['id'];
$menu = productosDAO::getMenuById($menuId);
$bebidas = productosDAO::getBebidas();
$complementos = productosDAO::getComplementos();
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-6 text-center modificar-image">
            <img src="<?= $menu['imagen'] ?>" alt="<?= $menu['nombre'] ?>" class="img-fluid">
        </div>
        <div class="col-6 text-center modificar-info">
            <h2>Selecciona tu bebida</h2>
            <div class="bebidas">
                <?php foreach ($bebidas as $bebida): ?>
                    <div class="bebida-box" onclick="selectBebida(<?= $bebida['id'] ?>)">
                        <img src="<?= $bebida['imagen'] ?>" alt="<?= $bebida['nombre'] ?>">
                        <p><?= $bebida['nombre'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <h2>Selecciona tu complemento</h2>
            <div class="complementos">
                <?php foreach ($complementos as $complemento): ?>
                    <div class="complemento-box" onclick="selectComplemento(<?= $complemento['id'] ?>)">
                        <img src="<?= $complemento['imagen'] ?>" alt="<?= $complemento['nombre'] ?>">
                        <p><?= $complemento['nombre'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="btn-pedir" onclick="addToCart(<?= $menu['id'] ?>)">Añadir al carrito</button>
        </div>
    </div>
</div>
<script>
let selectedBebida = null;
let selectedComplemento = null;

function selectBebida(id) {
    selectedBebida = id;
    document.querySelectorAll('.bebida-box').forEach(box => box.classList.remove('selected'));
    document.querySelector(`.bebida-box[onclick="selectBebida(${id})"]`).classList.add('selected');
}

function selectComplemento(id) {
    selectedComplemento = id;
    document.querySelectorAll('.complemento-box').forEach(box => box.classList.remove('selected'));
    document.querySelector(`.complemento-box[onclick="selectComplemento(${id})"]`).classList.add('selected');
}

function addToCart(menuId) {
    if (selectedBebida && selectedComplemento) {
        const formData = new FormData();
        formData.append('menuId', menuId);
        formData.append('bebidaId', selectedBebida);
        formData.append('complementoId', selectedComplemento);
        formData.append('nombre', '<?= $menu['nombre'] ?>');
        formData.append('descripcion', '<?= $menu['descripcion'] ?>');
        formData.append('precio', '<?= $menu['precio'] ?>');
        formData.append('imagen', '<?= $menu['imagen'] ?>');
        fetch('?controller=producto&action=añadirCarrito', {
            method: 'POST',
            body: formData
        }).then(response => response.json()).then(data => {
            if (data.success) {
                window.location.href = '?controller=producto&action=carrito';
            }
        });
    } else {
        alert('Por favor, selecciona una bebida y un complemento.');
    }
}
</script>
