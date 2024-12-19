function fetchProductos(tipo = null) { // Fetch de productos
    let url = 'controllers/apicontroller.php?action=obtenerProductos';
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
                    <td>${producto.precio}</td>
                    <td>${producto.tipo}</td>
                    <td><img src="${producto.imagen}" alt="${producto.nombre}" style="width: 50px; height: 50px;"></td>
                    <td><button class="delete">Eliminar</button> <button class="edit">Editar</button></td>
                `;
                tableBody.appendChild(row);
            });

            showTable('productosTable');
        })
        .catch(error => console.error('Error:', error));
}

//Funcion para eliminar un producto
function eliminarProducto(id_producto, tipo) {
    fetch(`controllers/apicontroller.php?action=eliminarProducto&id_producto=${id_producto}&tipo=${tipo}`)
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

    // Fetch los datos del producto
    fetch(`controllers/apicontroller.php?action=obtenerProducto&id_producto=${id_producto}&tipo=${tipo}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const producto = data.producto;
                formContainer.innerHTML = `
                    <input type="text" id="editNombre" placeholder="Nombre" value="${producto.nombre}">
                    <input type="text" id="editDescripcion" placeholder="Descripción" value="${producto.descripcion}">
                    <input type="text" id="editPrecio" placeholder="Precio" value="${producto.precio}">
                    <input type="text" id="editImagen" placeholder="Imagen URL" value="${producto.imagen}">
                    <button id="submitEditarProducto">Editar Producto</button>
                `;

                document.getElementById('submitEditarProducto').addEventListener('click', function() {
                    const nombre = document.getElementById('editNombre').value;
                    const descripcion = document.getElementById('editDescripcion').value;
                    const precio = document.getElementById('editPrecio').value;
                    const imagen = document.getElementById('editImagen').value;

                    fetch(`controllers/apicontroller.php?action=editarProducto&id_producto=${id_producto}&tipo=${tipo}`, {
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
    formContainer.innerHTML = `
        <input type="text" id="nombre" placeholder="Nombre">
        <input type="text" id="descripcion" placeholder="Descripción">
        <input type="text" id="precio" placeholder="Precio">
        <input type="text" id="imagen" placeholder="Imagen URL">
        <select id="tipo">
            <option value="menu">Menú</option>
            <option value="hamburguesa">Hamburguesa</option>
            <option value="bebida">Bebida</option>
            <option value="complemento">Complemento</option>
        </select>
        <button id="submitCrearProducto">Crear Producto</button>
    `;

    //Funcion para crear un producto con los datos introducidos
    document.getElementById('submitCrearProducto').addEventListener('click', function() {
        const nombre = document.getElementById('nombre').value;
        const descripcion = document.getElementById('descripcion').value;
        const precio = document.getElementById('precio').value;
        const imagen = document.getElementById('imagen').value;
        const tipo = document.getElementById('tipo').value;

        fetch('controllers/apicontroller.php?action=crearProducto', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ nombre, descripcion, precio, imagen, tipo })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                fetchProductos();
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

//Funcion para
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