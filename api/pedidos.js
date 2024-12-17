function fetchPedidos(action) {
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

function fetchProductos() {
    fetch('controllers/apicontroller.php?action=obtenerProductos')
        .then(response => response.json())
        .then(data => {
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
                `;
                tableBody.appendChild(row);
            });

            showTable('productosTable');
        })
        .catch(error => console.error('Error:', error));
}

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
                        <input type="checkbox" class="producto" data-id="${producto.id}" data-nombre="${producto.nombre}" data-precio="${producto.precio}">
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

function generarPedido() {
    const productos = [];
    document.querySelectorAll('.producto:checked').forEach(checkbox => {
        const cantidad = checkbox.parentElement.nextElementSibling.value;
        productos.push({
            id: checkbox.dataset.id,
            nombre: checkbox.dataset.nombre,
            precio: checkbox.dataset.precio,
            cantidad: cantidad
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
    .catch(error => console.error('Error:', error));
}

function showTable(tableId) {
    document.querySelectorAll('.table-container').forEach(container => {
        container.style.display = 'none';
    });
    document.getElementById('formContainer').style.display = 'none';
    document.getElementById(tableId).parentElement.style.display = 'block';
}

function fetchUsuarios() {
    fetch('controllers/apicontroller.php?action=obtenerUsuarios')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#usuariosTable tbody');
            tableBody.innerHTML = '';

            data.forEach(usuario => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${usuario.id_usuario}</td>
                    <td>${usuario.usuario}</td>
                    <td>${usuario.nombre}</td>
                    <td>${usuario.apellido}</td>
                    <td>${usuario.email}</td>
                `;
                tableBody.appendChild(row);
            });

            showTable('usuariosTable');
        })
        .catch(error => console.error('Error:', error));
}

document.getElementById('obtenerPedidos').addEventListener('click', function() {
    fetchPedidos('obtenerPedidos');
});

document.getElementById('ordenarPorUsuario').addEventListener('click', function() {
    fetchPedidos('ordenarPorUsuario');
});

document.getElementById('ordenarPorFecha').addEventListener('click', function() {
    fetchPedidos('ordenarPorFecha');
});

document.getElementById('ordenarPorPrecio').addEventListener('click', function() {
    fetchPedidos('ordenarPorPrecio');
});

document.getElementById('crearPedido').addEventListener('click', function() {
    crearPedido();
});

document.getElementById('obtenerUsuarios').addEventListener('click', function() {
    fetchUsuarios();
});

document.getElementById('obtenerProductos').addEventListener('click', function() {
    fetchProductos();
});

document.querySelector('#pedidosTable tbody').addEventListener('click', function(event) {
    if (event.target.classList.contains('delete')) {
        const id_pedido = event.target.closest('tr').querySelector('td').innerText;
        eliminarPedido(id_pedido);
    }
});
