function fetchPedidos(action) { // Funcion para obtener pedidos
    fetch(`controllers/apicontroller.php?action=${action}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#pedidosTable tbody');
            tableBody.innerHTML = '';

            data.forEach(pedido => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${pedido.id_pedido}</td>
                    <td>${pedido.usuario}</td>
                    <td>${pedido.fecha}</td>
                    <td>${pedido.total}</td>
                    <td>
                        <button class="edit">Editar</button>
                        <button class="delete">Eliminar</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            showTable('pedidosTable');
        })
        .catch(error => console.error('Error:', error));
}

// Funcion para eliminar pedido
function eliminarPedido(id_pedido) {
    fetch(`controllers/apicontroller.php?action=eliminarPedido&id_pedido=${id_pedido}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                fetchPedidos('obtenerPedidos');
            } else {
                console.error('Error:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Funcion para crear pedido
function crearPedido() {
    fetch('controllers/apicontroller.php?action=crearPedido')
        .then(response => response.json())
        .then(data => {
            document.querySelectorAll('.table-container').forEach(container => {
                container.style.display = 'none';
            });

            const formContainer = document.getElementById('formContainer');
            formContainer.style.display = 'block';
            formContainer.innerHTML = '';

            data.forEach(producto => {
                const div = document.createElement('div');
                div.innerHTML = `
                    <label>
                        <input type="checkbox" class="producto" data-id="${producto.id}" data-nombre="${producto.nombre}" data-precio="${producto.precio}" data-tipo="${producto.tipo}">
                        ${producto.nombre} - ${producto.precio}
                    </label>
                    <input type="number" class="cantidad" min="1" value="1">
                `;
                formContainer.appendChild(div);
            });

            const generarButton = document.createElement('button');
            generarButton.innerText = 'Generar Pedido';
            generarButton.addEventListener('click', generarPedido);
            formContainer.appendChild(generarButton);
        })
        .catch(error => console.error('Error:', error));
}

//Funcion para generar pedido
function generarPedido() {
    const productos = [];
    document.querySelectorAll('.producto:checked').forEach(checkbox => {
        const cantidad = checkbox.parentElement.nextElementSibling.value;
        productos.push({
            id: checkbox.dataset.id,
            nombre: checkbox.dataset.nombre,
            precio: checkbox.dataset.precio,
            cantidad: cantidad,
            tipo: checkbox.dataset.tipo
        });
    });

    fetch('controllers/apicontroller.php?action=generarPedido', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ productos: productos })
    })
    .then(response => response.json())
    .then(data => {
        alert('Pedido generado: ' + JSON.stringify(data));
        fetchPedidos('obtenerPedidos');
    })
    //.catch(error => console.error('Error:', error));
}

//Funcion para editar pedido
function editarPedido(id_pedido) {
    fetch(`controllers/apicontroller.php?action=editarPedido&id_pedido=${id_pedido}`)
        .then(response => response.json())
        .then(data => {
            document.querySelectorAll('.table-container').forEach(container => {
                container.style.display = 'none';
            });
            document.getElementById('filterButtons').style.display = 'none';

            const formContainer = document.getElementById('formContainer');
            formContainer.style.display = 'block';
            formContainer.innerHTML = '';

            data.productos.forEach(producto => {
                const div = document.createElement('div');
                div.innerHTML = `
                    <label>
                        ${producto.nombre} - ${producto.precio}
                        <input type="number" class="cantidad" min="1" value="${producto.cantidad}" data-id="${producto.id}" data-tipo="${producto.tipo}">
                    </label>
                    <button class="delete-producto">Eliminar</button>
                `;
                formContainer.appendChild(div);
            });

            const actualizarButton = document.createElement('button');
            actualizarButton.innerText = 'Actualizar Pedido';
            actualizarButton.classList.add('actualizar-pedido');
            actualizarButton.dataset.idPedido = id_pedido;
            actualizarButton.addEventListener('click', () => actualizarPedido(data.id_pedido));
            formContainer.appendChild(actualizarButton);

            const agregarProductoButton = document.createElement('button');
            agregarProductoButton.innerText = 'Agregar Producto';
            agregarProductoButton.addEventListener('click', () => mostrarProductosParaAgregar(data.id_pedido));
            formContainer.appendChild(agregarProductoButton);
        })
        .catch(error => console.error('Error:', error));
}

// Funcion para mostrar productos para agregar
function mostrarProductosParaAgregar(id_pedido) {
    fetch(`controllers/apicontroller.php?action=editarPedido&id_pedido=${id_pedido}`)
        .then(response => response.json())
        .then(data => {
            const existingProductos = data.productos.map(producto => producto.id);

            fetch('controllers/apicontroller.php?action=crearPedido')
                .then(response => response.json())
                .then(data => {
                    const formContainer = document.getElementById('formContainer');
                    formContainer.innerHTML = '';

                    data.forEach(producto => {
                        if (!existingProductos.includes(producto.id)) {
                            const div = document.createElement('div');
                            div.innerHTML = `
                                <label>
                                    <input type="checkbox" class="producto" data-id="${producto.id}" data-nombre="${producto.nombre}" data-precio="${producto.precio}" data-tipo="${producto.tipo}">
                                    ${producto.nombre} - ${producto.precio}
                                </label>
                                <input type="number" class="cantidad" min="1" value="1">
                            `;
                            formContainer.appendChild(div);
                        }
                    });

                    const agregarButton = document.createElement('button');
                    agregarButton.innerText = 'Agregar al Pedido';
                    agregarButton.addEventListener('click', () => agregarProductosAlPedido(id_pedido));
                    formContainer.appendChild(agregarButton);
                })
                .catch(error => console.error('Error:', error));
        })
        .catch(error => console.error('Error:', error));
}

// Funcion para agregar productos al pedido
function agregarProductosAlPedido(id_pedido) {
    const productos = [];
    document.querySelectorAll('.producto:checked').forEach(checkbox => {
        const cantidad = checkbox.parentElement.nextElementSibling.value;
        productos.push({
            id: checkbox.dataset.id,
            nombre: checkbox.dataset.nombre,
            precio: checkbox.dataset.precio,
            cantidad: cantidad,
            tipo: checkbox.dataset.tipo
        });
    });

    if (productos.length > 0) {
        fetch('controllers/apicontroller.php?action=agregarProductos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_pedido: id_pedido, productos: productos })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            editarPedido(id_pedido); // Redirigir a la pagina de edicion de pedido
        })
        .catch(error => console.error('Error:', error));
    } else {
        alert('No new products to add.');
    }
}

// Funcion para actualizar pedido
function actualizarPedido(id_pedido) {
    const productos = [];
    document.querySelectorAll('.cantidad').forEach(input => {
        const precio = parseFloat(input.closest('label').innerText.split('-')[1].trim());
        productos.push({
            id: input.dataset.id,
            tipo: input.dataset.tipo,
            cantidad: parseInt(input.value, 10), // Asegurarse de que la cantidad sea un numero integer
            precio: precio
        });
    });

    fetch('controllers/apicontroller.php?action=actualizarPedido', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id_pedido: id_pedido, productos: productos })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        fetchPedidos('obtenerPedidos');
    })
    .catch(error => console.error('Error:', error));
}

//Funcion para eliminar producto de pedido
function eliminarProductoDePedido(id_pedido, id_producto, tipo) {
    fetch(`controllers/apicontroller.php?action=eliminarProductoDePedido&id_pedido=${id_pedido}&id_producto=${id_producto}&tipo=${tipo}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                editarPedido(id_pedido); // Redirigir a la pagina de edicion de pedido
            } else {
                console.error('Error:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

//Funcion para mostrar tabla
function showTable(tableId) {
    document.querySelectorAll('.table-container').forEach(container => {
        container.style.display = 'none';
    });
    document.getElementById('formContainer').style.display = 'none';
    document.getElementById('filterButtons').style.display = 'none';

    document.getElementById(tableId).parentElement.style.display = 'block';

    if (tableId === 'productosTable') {
        document.getElementById('filterButtons').style.display = 'block';
    }
}

// Obtenemos los pedidos
document.getElementById('obtenerPedidos').addEventListener('click', function() {
    fetchPedidos('obtenerPedidos');
});

// Ordenamos los pedidos por usuarios
document.getElementById('ordenarPorUsuario').addEventListener('click', function() {
    fetchPedidos('ordenarPorUsuario');
});

// Ordenamos los pedidos por fecha
document.getElementById('ordenarPorFecha').addEventListener('click', function() {
    fetchPedidos('ordenarPorFecha');
});

// Ordenamos los pedidos por precio
document.getElementById('ordenarPorPrecio').addEventListener('click', function() {
    fetchPedidos('ordenarPorPrecio');
});

// Ordenamos los pedidos por total
document.getElementById('crearPedido').addEventListener('click', function() {
    crearPedido();
});

// Eliminamos un pedido
document.querySelector('#pedidosTable tbody').addEventListener('click', function(event) {
    if (event.target.classList.contains('delete')) {
        const id_pedido = event.target.closest('tr').querySelector('td').innerText;
        eliminarPedido(id_pedido);
    }
});

// Editar un pedido
document.querySelector('#pedidosTable tbody').addEventListener('click', function(event) {
    if (event.target.classList.contains('edit')) {
        const id_pedido = event.target.closest('tr').querySelector('td').innerText;
        editarPedido(id_pedido);
    }
});

// Lista de eventos para agregar productos al pedido
document.getElementById('formContainer').addEventListener('click', function(event) {
    if (event.target.classList.contains('delete-producto')) {
        const id_pedido = document.querySelector('#formContainer button.actualizar-pedido').dataset.idPedido;
        const id_producto = event.target.previousElementSibling.querySelector('.cantidad').dataset.id;
        const tipo = event.target.previousElementSibling.querySelector('.cantidad').dataset.tipo;
        eliminarProductoDePedido(id_pedido, id_producto, tipo);
    }
});
