function fetchProductos(tipo = null) { // Fetch de productos
    let url = 'controllers/apiController.php?action=obtenerProductos';
    if (tipo) {
        url += `&tipo=${tipo}`;
    }

    fetch(url) 
        .then(response => response.json())
        .then(data => {
            if (!Array.isArray(data)) {
                console.error('Error: Array no recivida correctamente en su lugar ha llegado: ', data);
                return;
            }

            const tableBody = document.querySelector('#productosTable tbody');
            tableBody.innerHTML = '';

            data.forEach(producto => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${producto.id}</td>
                    <td>${producto.nombre}</td>
                    <td>${producto.descripcion}</td>
                    <td data-original-price="${producto.precio}">${producto.precio} €</td>
                    <td>${producto.tipo}</td>
                    <td><img src="${producto.imagen}" alt="${producto.nombre}" style="width: 120px; height: 120px;"></td>
                    <td><button class="edit btn-action">Editar</button><button class="delete btn-action">Eliminar</button></td>
                `;
                tableBody.appendChild(row);
            });

            showTable('productosTable');
            updatePrices(); // Actualizar precios
        })
        .catch(error => console.error('Error:', error));
}

//Funcion para eliminar un producto
function eliminarProducto(id_producto, tipo) {
    fetch(`controllers/apiController.php?action=eliminarProducto&id_producto=${id_producto}&tipo=${tipo}`)
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                fetchProductos(tipo);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Funcion para editar un producto
function editarProducto(id_producto, tipo) {
    document.querySelectorAll('.table-container').forEach(container => {
        container.style.display = 'none';
    });

    const formContainer = document.getElementById('formContainer');
    formContainer.style.display = 'block';
    formContainer.classList.add('form-registro'); 
    formContainer.classList.add('form-center');  

    // Fetch los datos del producto
    fetch(`controllers/apiController.php?action=obtenerProducto&id_producto=${id_producto}&tipo=${tipo}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const producto = data.producto;
                formContainer.innerHTML = `
                    <label class="form-label" for="editNombre">Nombre</label>
                    <input type="text" id="editNombre" class="form-input" placeholder="Nombre" value="${producto.nombre}">
                    <label class="form-label" for="editDescripcion">Descripción</label>
                    <input type="text" id="editDescripcion" class="form-input" placeholder="Descripción" value="${producto.descripcion}">
                    <label class="form-label" for="editPrecio">Precio</label>
                    <input type="text" id="editPrecio" class="form-input" placeholder="Precio" value="${producto.precio}">
                    <label class="form-label" for="editImagen">Imagen</label>
                    <select id="editImagen" class="form-input"></select>
                    <button id="submitEditarProducto" class="btn-admin">Editar Producto</button>
                `;

                // Fetch the list of images
                fetch(`controllers/apiController.php?action=obtenerImagenes&tipo=${tipo}`)
                    .then(response => response.json())
                    .then(images => {
                        const imagenSelect = document.getElementById('editImagen');
                        images.forEach(image => {
                            const option = document.createElement('option');
                            option.value = `views/img/${tipo}s/${image}`;
                            option.textContent = image;
                            if (producto.imagen === `views/img/${tipo}s/${image}`) {
                                option.selected = true;
                            }
                            imagenSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error:', error));

                document.getElementById('submitEditarProducto').addEventListener('click', function() {
                    const nombre = document.getElementById('editNombre').value;
                    const descripcion = document.getElementById('editDescripcion').value;
                    const precio = document.getElementById('editPrecio').value;
                    const imagen = document.getElementById('editImagen').value;

                    fetch(`controllers/apiController.php?action=editarProducto&id_producto=${id_producto}&tipo=${tipo}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ nombre, descripcion, precio, imagen })
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.status === 'success') {
                            fetchProductos(tipo);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

//Funcion para mostrar la tabla
document.getElementById('obtenerProductos').addEventListener('click', function() {
    fetchProductos();
});

//Funciones para filtrar productos por hamburguesas
document.getElementById('filtrarHamburguesas').addEventListener('click', function() {
    fetchProductos('hamburguesa');
});

//Funciones para filtrar productos por menus
document.getElementById('filtrarMenus').addEventListener('click', function() {
    fetchProductos('menu');
});

//Funciones para filtrar productos por bebidas
document.getElementById('filtrarBebidas').addEventListener('click', function() {
    fetchProductos('bebida');
});

//Funciones para filtrar productos por complementos
document.getElementById('filtrarComplementos').addEventListener('click', function() {
    fetchProductos('complemento');
});

//Funcion para crear un producto
document.getElementById('crearProducto').addEventListener('click', function() {
    document.querySelectorAll('.table-container').forEach(container => {
        container.style.display = 'none';
    });

    const formContainer = document.getElementById('formContainer');
    formContainer.style.display = 'block';
    formContainer.classList.add('form-registro');
    formContainer.classList.add('form-center');
    formContainer.innerHTML = `
        <label class="form-label" for="nombre">Nombre</label>
        <input type="text" id="nombre" class="form-input" placeholder="Nombre">
        <label class="form-label" for="descripcion">Descripción</label>
        <input type="text" id="descripcion" class="form-input" placeholder="Descripción">
        <label class="form-label" for="precio">Precio</label>
        <input type="text" id="precio" class="form-input" placeholder="Precio">
        <label class="form-label" for="tipo">Tipo</label>
        <select id="tipo" class="form-input">
            <option value="hamburguesa">Hamburguesa</option>
            <option value="bebida">Bebida</option>
            <option value="complemento">Complemento</option>
            <option value="menu">Menú</option>
        </select>
        <div id="hamburguesaSelectContainer" style="display: none;">
            <label class="form-label" for="id_hamburguesa">Hamburguesa</label>
            <select id="id_hamburguesa" class="form-input"></select>
        </div>
        <label class="form-label" for="imagen">Imagen</label>
        <input type="file" id="imagen" class="form-input">
        <button id="submitCrearProducto" class="btn-admin">Crear Producto</button>
        <div id="loading" class="loading" style="display: none;"></div>
    `;

    // Cargar hamburguesas en caso de seleccionar menu
    document.getElementById('tipo').addEventListener('change', function() {
        const hamburguesaSelectContainer = document.getElementById('hamburguesaSelectContainer');
        if (this.value === 'menu') {
            fetch('controllers/apiController.php?action=obtenerProductos&tipo=hamburguesa')
                .then(response => response.json())
                .then(data => {
                    const hamburguesaSelect = document.getElementById('id_hamburguesa');
                    hamburguesaSelect.innerHTML = '';
                    data.forEach(hamburguesa => {
                        const option = document.createElement('option');
                        option.value = hamburguesa.id;
                        option.textContent = hamburguesa.nombre;
                        hamburguesaSelect.appendChild(option);
                    });
                    hamburguesaSelectContainer.style.display = 'block';
                })
                .catch(error => console.error('Error:', error));
        } else {
            hamburguesaSelectContainer.style.display = 'none';
        }
    });

    // Crear producto al hacer click en el boton
    document.getElementById('submitCrearProducto').addEventListener('click', function() {
        const nombre = document.getElementById('nombre').value;
        const descripcion = document.getElementById('descripcion').value;
        const precio = document.getElementById('precio').value;
        const tipo = document.getElementById('tipo').value;
        const id_hamburguesa = tipo === 'menu' ? document.getElementById('id_hamburguesa').value : null; // Solo en caso de ser menu
        const imagen = document.getElementById('imagen').files[0];

        const formData = new FormData();
        formData.append('nombre', nombre);
        formData.append('descripcion', descripcion);
        formData.append('precio', precio);
        formData.append('tipo', tipo);
        formData.append('imagen', imagen);
        if (id_hamburguesa) {
            formData.append('id_hamburguesa', id_hamburguesa);
        }

        document.getElementById('loading').style.display = 'block'; // Mostrar el símbolo de carga

        fetch('controllers/apiController.php?action=crearProducto', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('loading').style.display = 'none'; // Ocultar el símbolo de carga
            alert(data.message);
            if (data.status === 'success') {
                fetchProductos();
            }
        })
        .catch(error => {
            document.getElementById('loading').style.display = 'none'; // Ocultar el símbolo de carga
            console.error('Error:', error);
        });
    });
});

//Funcion para mostrar la tabla de productos
document.querySelector('#productosTable tbody').addEventListener('click', function(event) {
    if (event.target.classList.contains('delete')) {
        const row = event.target.closest('tr');
        const id_producto = row.querySelector('td').innerText;
        const tipo = row.querySelector('td:nth-child(5)').innerText;
        eliminarProducto(id_producto, tipo);
    } else if (event.target.classList.contains('edit')) {
        const row = event.target.closest('tr');
        const id_producto = row.querySelector('td').innerText;
        const tipo = row.querySelector('td:nth-child(5)').innerText;
        editarProducto(id_producto, tipo);
    }
});

//Gestionar visibilidad de las tablas
function showTable(tableId) {
    document.querySelectorAll('.table-container').forEach(container => {
        container.style.display = 'none';
    });
    document.getElementById('formContainer').style.display = 'none';
    document.getElementById('filterButtons').style.display = 'none';
    document.getElementById('currencyContainerPedidos').style.display = 'none';
    document.getElementById('currencyContainerProductos').style.display = 'none';

    document.getElementById(tableId).parentElement.style.display = 'block';

    if (tableId === 'productosTable') {
        document.getElementById('currencyContainerProductos').style.display = 'block';
        document.getElementById('filterButtons').style.display = 'block';
    } else if (tableId === 'pedidosTable') {
        document.getElementById('currencyContainerPedidos').style.display = 'block';
    }
}