<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-6 text-center modificar-image">
            <img src="<?= $data['menu']['imagen'] ?>" alt="<?= $data['menu']['nombre'] ?>" class="img-fluid">
        </div>
        <div class="col-6 text-center modificar-info">
            <h2>Selecciona tu bebida</h2>
            <div class="bebidas">
                <?php foreach ($data['bebidas'] as $bebida): ?>
                    <div class="bebida-box" onclick="selectBebida(<?= $bebida->getId() ?>)">
                        <img src="<?= $bebida->getImagen() ?>" alt="<?= $bebida->getNombre() ?>">
                        <p><?= $bebida->getNombre() ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <h2>Selecciona tu complemento</h2>
            <div class="complementos">
                <?php foreach ($data['complementos'] as $complemento): ?>
                    <div class="complemento-box" onclick="selectComplemento(<?= $complemento->getId() ?>)">
                        <img src="<?= $complemento->getImagen() ?>" alt="<?= $complemento->getNombre() ?>">
                        <p><?= $complemento->getNombre() ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="btn-pedir" onclick="addToCart(<?= $data['menu']['id'] ?>)">Añadir al carrito</button>
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
        formData.append('nombre', '<?= $data['menu']['nombre'] ?>');
        formData.append('descripcion', '<?= $data['menu']['descripcion'] ?>');
        formData.append('precio', '<?= $data['menu']['precio'] ?>');
        formData.append('imagen', '<?= $data['menu']['imagen'] ?>');
        formData.append('tipo', 'menu');
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
