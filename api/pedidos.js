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
