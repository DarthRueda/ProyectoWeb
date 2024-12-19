function fetchUsuarios() { //Funcion para obtener los usuarios
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
                    <td>${usuario.telefono}</td>
                    <td>
                        <button class="edit">Editar</button>
                        <button class="delete">Eliminar</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            showTable('usuariosTable');
        })
        .catch(error => console.error('Error:', error));
}

//Funcion para eliminar un usuario
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

//Muestra la tabla de usuarios
document.querySelector('#usuariosTable tbody').addEventListener('click', function(event) {
    if (event.target.classList.contains('delete')) {
        const id_usuario = event.target.closest('tr').querySelector('td').innerText;
        eliminarUsuario(id_usuario);
    }
    if (event.target.classList.contains('edit')) {
        const row = event.target.closest('tr');
        const usuario = {
            id_usuario: row.querySelector('td:nth-child(1)').innerText,
            usuario: row.querySelector('td:nth-child(2)').innerText,
            nombre: row.querySelector('td:nth-child(3)').innerText,
            apellido: row.querySelector('td:nth-child(4)').innerText,
            email: row.querySelector('td:nth-child(5)').innerText,
            telefono: row.querySelector('td:nth-child(6)').innerText
        };
        showEditarUsuarioForm(usuario);
    }
});

//Funcion para crear un usuario
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
            fetchUsuarios();
        }
    })
    .catch(error => console.error('Error:', error));
}

//Funcion para mostrar formulario de creacion de usuario
function showCrearUsuarioForm() {
    document.querySelectorAll('.table-container').forEach(container => {
        container.style.display = 'none';
    });
    const formContainer = document.getElementById('formContainer');
    formContainer.style.display = 'block';
    formContainer.innerHTML = `
        <input type="text" id="usuario" placeholder="Usuario">
        <input type="text" id="nombre" placeholder="Nombre">
        <input type="text" id="apellido" placeholder="Apellido">
        <input type="email" id="email" placeholder="Email">
        <input type="password" id="contrasena" placeholder="Contraseña">
        <input type="text" id="telefono" placeholder="Teléfono">
        <button id="submitCrearUsuario">Crear Usuario</button>
    `;
    document.getElementById('submitCrearUsuario').addEventListener('click', function() {
        crearUsuario();
    });
}

//Funcion para mostrar el formulario de edicion de usuario
function showEditarUsuarioForm(usuario) {
    document.querySelectorAll('.table-container').forEach(container => {
        container.style.display = 'none';
    });
    const formContainer = document.getElementById('formContainer');
    formContainer.style.display = 'block';
    formContainer.innerHTML = `
        <input type="text" id="usuario" placeholder="Usuario" value="${usuario.usuario}">
        <input type="text" id="nombre" placeholder="Nombre" value="${usuario.nombre}">
        <input type="text" id="apellido" placeholder="Apellido" value="${usuario.apellido}">
        <input type="email" id="email" placeholder="Email" value="${usuario.email}">
        <input type="password" id="contrasena" placeholder="Contraseña">
        <input type="text" id="telefono" placeholder="Teléfono" value="${usuario.telefono}">
        <button id="submitEditarUsuario">Editar Usuario</button>
    `;
    document.getElementById('submitEditarUsuario').addEventListener('click', function() {
        editarUsuario(usuario.id_usuario);
    });
}

//Funcion para editar un usuario
function editarUsuario(id_usuario) {
    const usuario = document.getElementById('usuario').value;
    const nombre = document.getElementById('nombre').value;
    const apellido = document.getElementById('apellido').value;
    const email = document.getElementById('email').value;
    const contrasena = document.getElementById('contrasena').value;
    const telefono = document.getElementById('telefono').value;

    fetch(`controllers/apicontroller.php?action=editarUsuario&id_usuario=${id_usuario}`, {
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
            fetchUsuarios();
        }
    })
    //.catch(error => console.error('Error:', error));
}

//Obtener usuarios al cargar la pagina
document.getElementById('obtenerUsuarios').addEventListener('click', function() {
    fetchUsuarios();
});

//Mostrar formulario de creacion de usuario
document.getElementById('crearUsuario').addEventListener('click', function() {
    showCrearUsuarioForm();
});
