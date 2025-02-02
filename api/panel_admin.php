<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="api/admin.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery, necesario para AJAX -->
</head>
<body>
    <div class="button-wrapper">
        <div class="button-container">
            <!-- Botones de pedidos -->
            <div class="button-group">
                <h3>Pedidos</h3>
                <button id="obtenerPedidos" class="btn-admin">Obtener Pedidos</button>
                <button id="ordenarPorUsuario" class="btn-admin">Ordenar por Usuario</button>
                <button id="ordenarPorFecha" class="btn-admin">Ordenar por Fecha</button>
                <button id="ordenarPorPrecio" class="btn-admin">Ordenar por Precio</button>
                <button id="crearPedido" class="btn-admin">Crear Pedido</button>
            </div>
            <!-- Botones de productos -->
            <div class="button-group">
                <h3>Productos</h3>
                <button id="obtenerProductos" class="btn-admin">Obtener Productos</button>
                <button id="crearProducto" class="btn-admin">Crear Producto</button>
                <div id="filterButtons" style="display: none;">
                    <button id="filtrarHamburguesas" class="btn-admin">Filtrar Hamburguesas</button>
                    <button id="filtrarMenus" class="btn-admin">Filtrar Menús</button>
                    <button id="filtrarBebidas" class="btn-admin">Filtrar Bebidas</button>
                    <button id="filtrarComplementos" class="btn-admin">Filtrar Complementos</button>
                </div>
            </div>
            <!-- Botones de usuarios -->
            <div class="button-group">
                <h3>Usuarios</h3>
                <button id="obtenerUsuarios" class="btn-admin">Obtener Usuarios</button>
                <button id="crearUsuario" class="btn-admin">Crear Usuario</button>
            </div>
            <!-- Botones de logs -->
            <div class="button-group">
                <h3>Logs</h3>
                <button id="verLogs" class="btn-admin">Ver Logs</button>
                <button id="clearLogs" class="btn-admin" style="display: none;">Borrar Logs</button>
            </div>
            <!-- Botón de navegación -->
            <div class="button-group">
                <h3>Navegación</h3>
                <button id="volverMenuUsuario" class="btn-admin">Volver al Menú de Usuario</button>
            </div>
        </div>
    </div>
    <!-- Formulario de creación de pedidos -->
    <div id="formContainer" class="form-center" style="display: none;">
        <select id="usuarioSelect">
            <option value="">Pedido sin Usuario</option>
        </select>
        <input type="text" id="usuario" placeholder="Usuario">
        <input type="text" id="nombre" placeholder="Nombre">
        <input type="text" id="apellido" placeholder="Apellido">
        <input type="email" id="email" placeholder="Email">
        <input type="password" id="contrasena" placeholder="Contraseña">
        <input type="text" id="telefono" placeholder="Teléfono">
        <button id="submitCrearUsuario" class="btn-admin">Crear Usuario</button>
    </div>
    <!-- Formulario de edición de productos -->
    <div id="formContainer" style="display: none;">
    </div>
    <!-- Tablas de pedidos -->
    <div id="pedidosTableContainer" class="table-container" style="display: none;">
        <table id="pedidosTable" border="1">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Pagado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <!-- Moneda menu -->
        <div id="currencyContainerPedidos" class="currency-container" style="display: none;">
            <h3>Moneda</h3>
            <select id="currencySelectorPedidos">
                <option value="EUR">Euro</option>
                <!-- Las otras monedas aparecerán aqui automáticamente -->
            </select>
        </div>
    </div>
    <!-- Tabla de usuarios -->
    <div id="usuariosTableContainer" class="table-container" style="display: none;">
        <table id="usuariosTable" border="1">
            <thead>
                <tr>
                    <th>ID Usuario</th>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Administrador</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>usuario1</td>
                    <td>Nombre1</td>
                    <td>Apellido1</td>
                    <td>email1@example.com</td>
                    <td>
                        <button class="editUser btn-action">Editar</button>
                        <button class="delete btn-action">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Tabla de productos -->
    <div id="productosTableContainer" class="table-container" style="display: none;">
        <table id="productosTable" border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Tipo</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Producto1</td>
                    <td>Descripción1</td>
                    <td>10.00</td>
                    <td>Tipo1</td>
                    <td>Imagen1</td>
                    <td>
                        <button class="edit btn-action">Editar</button>
                        <button class="delete btn-action">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Moneda menu -->
        <div id="currencyContainerProductos" class="currency-container" style="display: none;">
            <h3>Moneda</h3>
            <select id="currencySelectorProductos">
                <option value="EUR">Euro</option>
                <!-- Las otras monedas aparecerán aqui automáticamente -->
            </select>
        </div>
    </div>
    <!-- Tabla de logs -->
    <div id="logsContainer" class="table-container" style="display: none;">
        <table id="logsTable" border="1">
            <thead>
                <tr>
                    <th>Log</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <script src="api/pedidos.js"></script>
    <script src="api/usuarios.js"></script>
    <script src="api/productos.js"></script>
    <script src="api/logs.js"></script>
    <script src="api/monedas.js"></script>
    <script>
        document.getElementById('volverMenuUsuario').addEventListener('click', function() {
            window.location.href = '?controller=usuario&action=menu_usuario';
        });
    </script>
    <style>
        .currency-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</body>
</html>