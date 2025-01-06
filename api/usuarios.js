function fetchUsuarios() { //Funcion para obtener los usuarios
    fetch('/controllers/apiController.php?action=obtenerUsuarios')
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
                    <td>${usuario.administrador}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="edit btn-action">Editar</button>
                            <button class="delete btn-action">Eliminar</button>
                        </div>
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
    fetch(`/controllers/apiController.php?action=eliminarUsuario&id_usuario=${id_usuario}`, {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response:', data); // Log de la respuesta
        if (data.status === 'success') {
            fetchUsuarios();
        } else {
            console.error('Error:', data.message);
        }
    })
    //.catch(error => console.error('Error:', error));
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
            telefono: row.querySelector('td:nth-child(6)').innerText,
            administrador: row.querySelector('td:nth-child(7)').innerText
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
    const administrador = document.getElementById('administrador').value;

    fetch('/controllers/apiController.php?action=crearUsuario', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ usuario, nombre, apellido, email, contrasena, telefono, administrador })
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
    formContainer.classList.add('form-registro'); // Añadir clase para centrar el formulario
    formContainer.innerHTML = `
        <label class="form-label" for="usuario">Usuario</label>
        <input type="text" id="usuario" class="form-input" placeholder="Usuario">
        <label class="form-label" for="nombre">Nombre</label>
        <input type="text" id="nombre" class="form-input" placeholder="Nombre">
        <label class="form-label" for="apellido">Apellido</label>
        <input type="text" id="apellido" class="form-input" placeholder="Apellido">
        <label class="form-label" for="email">Email</label>
        <input type="email" id="email" class="form-input" placeholder="Email">
        <label class="form-label" for="contrasena">Contraseña</label>
        <input type="password" id="contrasena" class="form-input" placeholder="Contraseña">
        <label class="form-label" for="telefono">Teléfono</label>
        <input type="text" id="telefono" class="form-input" placeholder="Teléfono">
        <label class="form-label" for="administrador">Administrador</label>
        <select id="administrador" class="form-input">
            <option value="0">No Admin</option>
            <option value="1">Admin</option>
        </select>
        <button id="submitCrearUsuario" class="btn-admin">Crear Usuario</button>
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
    formContainer.classList.add('form-registro');
    formContainer.innerHTML = `
        <label class="form-label" for="usuario">Usuario</label>
        <input type="text" id="usuario" class="form-input" placeholder="Usuario" value="${usuario.usuario}">
        <label class="form-label" for="nombre">Nombre</label>
        <input type="text" id="nombre" class="form-input" placeholder="Nombre" value="${usuario.nombre}">
        <label class="form-label" for="apellido">Apellido</label>
        <input type="text" id="apellido" class="form-input" placeholder="Apellido" value="${usuario.apellido}">
        <label class="form-label" for="email">Email</label>
        <input type="email" id="email" class="form-input" placeholder="Email" value="${usuario.email}">
        <label class="form-label" for="telefono">Teléfono</label>
        <input type="text" id="telefono" class="form-input" placeholder="Teléfono" value="${usuario.telefono}">
        <label class="form-label" for="administrador">Administrador</label>
        <select id="administrador" class="form-input">
            <option value="0" ${usuario.administrador === 'False' ? 'selected' : ''}>No Admin</option>
            <option value="1" ${usuario.administrador === 'True' ? 'selected' : ''}>Admin</option>
        </select>
        <button id="submitEditarUsuario" class="btn-admin">Editar Usuario</button>
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
    const telefono = document.getElementById('telefono').value;
    const administrador = document.getElementById('administrador').value;

    fetch(`/controllers/apiController.php?action=editarUsuario&id_usuario=${id_usuario}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ usuario, nombre, apellido, email, telefono, administrador })
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

//Obtener usuarios al cargar la pagina
document.getElementById('obtenerUsuarios').addEventListener('click', function() {
    fetchUsuarios();
});

//Mostrar formulario de creacion de usuario
document.getElementById('crearUsuario').addEventListener('click', function() {
    showCrearUsuarioForm();
});
