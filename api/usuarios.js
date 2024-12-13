document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('obtenerPedidos').addEventListener('click', () => getPedidos());
    document.getElementById('ordenarPorUsuario').addEventListener('click', () => getPedidos('usuario'));
    document.getElementById('ordenarPorFecha').addEventListener('click', () => getPedidos('fecha'));
    document.getElementById('ordenarPorPrecio').addEventListener('click', () => getPedidos('total'));
    document.getElementById('crearPedido').addEventListener('click', () => showCreatePedidoForm());
    document.getElementById('modificarPedido').addEventListener('click', () => alert('Modificar Pedido'));
    document.getElementById('eliminarPedido').addEventListener('click', () => getPedidos('total', true));
    document.getElementById('añadirPedido').addEventListener('click', () => createPedido());
});

const API_URL = 'http://localhost/ProyectoWeb/api/api.php?endpoint=pedidos';

async function getPedidos(orderBy = '', showDeleteButton = false) {
    const url = orderBy ? `${API_URL}&orderBy=${orderBy}` : API_URL;
    const respuesta = await fetch(url);
    const datos = await respuesta.json();
    const tableBody = document.getElementById('pedidosTable').getElementsByTagName('tbody')[0];

    tableBody.innerHTML = ''; // Limpiar filas existentes

    datos.data
        .filter(pedido => orderBy !== 'usuario' || pedido.usuario) // Filtrar pedidos sin usuario si se ordena por usuario
        .forEach(pedido => {
            const row = tableBody.insertRow();
            row.insertCell(0).innerText = pedido.id_pedido;
            row.insertCell(1).innerText = pedido.usuario ? `${pedido.usuario} (${pedido.id_usuario})` : 'El usuario que ha realizado este pedido no estaba registrado';
            row.insertCell(2).innerText = pedido.fecha;
            row.insertCell(3).innerText = pedido.total;
            if (showDeleteButton) {
                const deleteCell = row.insertCell(4);
                const deleteButton = document.createElement('button');
                deleteButton.innerText = 'Eliminar Pedido';
                deleteButton.addEventListener('click', () => deletePedido(pedido.id_pedido));
                deleteCell.appendChild(deleteButton);
            }
        });
}

async function deletePedido(id_pedido) { // Borra un pedido
    const respuesta = await fetch(`${API_URL}&id_pedido=${id_pedido}`, {
        method: 'DELETE'
    });
    const datos = await respuesta.json();
    if (datos.estado === 'Exito') {
        alert('Pedido eliminado con éxito');
        getPedidos(); // Refrescar la tabla
    } else {
        alert('Error al eliminar el pedido');
    }
}

async function showCreatePedidoForm() {
    const productos = await fetchAllProducts();
    const formContainer = document.getElementById('formContainer');
    formContainer.innerHTML = `
        <h3>Crear Pedido</h3>
        <div id="productosList">
            ${productos.map(producto => `
                <div>
                    <input type="checkbox" id="producto-${producto.id}" data-id="${producto.id}" data-tipo="${producto.tipo}" data-precio="${producto.precio}">
                    <label for="producto-${producto.id}">${producto.nombre} (${producto.tipo}) - $${producto.precio}</label>
                </div>
            `).join('')}
        </div>
        <button id="añadirPedido">Añadir Pedido</button>
    `;
}

async function fetchAllProducts() {
    const response = await fetch('http://localhost/ProyectoWeb/api/api.php?endpoint=productos');
    const data = await response.json();
    return data.data;
}

async function createPedido() {
    const selectedProducts = Array.from(document.querySelectorAll('#productosList input:checked')).map(input => ({
        id: input.dataset.id,
        tipo: input.dataset.tipo,
        precio: parseFloat(input.dataset.precio)
    }));

    const id_usuario = prompt("Ingrese el ID del usuario:");
    if (!id_usuario) {
        alert("ID de usuario es requerido");
        return;
    }

    const response = await fetch('http://localhost/ProyectoWeb/api/api.php?endpoint=pedidos', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ productos: selectedProducts, id_usuario })
    });

    const result = await response.json();
    if (result.estado === 'Exito') {
        alert('Pedido creado con éxito');
        getPedidos();
    } else {
        alert('Error al crear el pedido');
    }
}