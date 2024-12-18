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
                    <td>
                        <button class="delete">Eliminar</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            showTable('usuariosTable');
        })
        .catch(error => console.error('Error:', error));
}

function eliminarUsuario(id_usuario) {
    fetch(`controllers/apicontroller.php?action=eliminarUsuario&id_usuario=${id_usuario}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                fetchUsuarios();
            } else {
                console.error('Error:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

document.querySelector('#usuariosTable tbody').addEventListener('click', function(event) {
    if (event.target.classList.contains('delete')) {
        const id_usuario = event.target.closest('tr').querySelector('td').innerText;
        eliminarUsuario(id_usuario);
    }
});

function crearUsuario() {
    const usuario = document.getElementById('usuario').value;
    const nombre = document.getElementById('nombre').value;
    const apellido = document.getElementById('apellido').value;
    const email = document.getElementById('email').value;
    const contrasena = document.getElementById('contrasena').value;
    const telefono = document.getElementById('telefono').value;

    fetch('controllers/apicontroller.php?action=crearUsuario', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ usuario, nombre, apellido, email, contrasena, telefono })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === 'success') {
            
        }
    })
    //.catch(error => console.error('Error:', error));
}

function showCrearUsuarioForm() {
    document.querySelectorAll('.table-container').forEach(container => {
        container.style.display = 'none';
    });
    document.getElementById('formContainer').style.display = 'block';
}

document.getElementById('obtenerUsuarios').addEventListener('click', function() {
    fetchUsuarios();
});

document.getElementById('submitCrearUsuario').addEventListener('click', function() {
    crearUsuario();
});

document.getElementById('crearUsuario').addEventListener('click', function() {
    showCrearUsuarioForm();
});
