document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('eliminarPedido').addEventListener('click', () => getPedidos('total', true));
});

const API_URL = 'http://localhost/ProyectoWeb/api/api.php?endpoint=pedidos';

async function getPedidos(orderBy = '', showDeleteButton = false) {
    const url = orderBy ? `${API_URL}&orderBy=${orderBy}` : API_URL;
    const respuesta = await fetch(url);
    const datos = await respuesta.json();
    const tableBody = document.getElementById('pedidosTable').getElementsByTagName('tbody')[0];

    tableBody.innerHTML = ''; // Clear existing rows

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

async function deletePedido(id_pedido) {
    const respuesta = await fetch(`${API_URL}&id_pedido=${id_pedido}`, {
        method: 'DELETE'
    });
    const datos = await respuesta.json();
    if (datos.estado === 'Exito') {
        alert('Pedido eliminado con éxito');
        getPedidos(); // Refresh the table
    } else {
        alert('Error al eliminar el pedido');
    }
}