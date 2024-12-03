<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) { // Comprobar si el usuario ha iniciado sesión
    header("Location: index.php?controller=usuario&action=login");
    exit;
}

$editing = isset($_GET['edit']) && $_GET['edit'] == 'true'; // Comprobar si el usuario está editando su información
?>
<!--  Este formulario muestra la información del usuario y permite editarla si el usuario está en modo edición -->
<div class="user-info" style="max-width: 600px; margin: 0 auto;">
    <h2>Información del Usuario</h2>
    <form method="POST" action="?controller=usuario&action=actualizar_datos">
        <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">
        <label for="nombre" class="form-label">Nombre:</label>
        <input type="text" id="nombre" name="nombre" class="form-input" value="<?php echo $_SESSION['nombre']; ?>" readonly><br>
        
        <label for="apellido" class="form-label">Apellido:</label>
        <input type="text" id="apellido" name="apellido" class="form-input" value="<?php echo $_SESSION['apellido']; ?>" <?php echo $editing ? '' : 'readonly'; ?>><br>
        
        <label for="email" class="form-label">Email:</label>
        <input type="email" id="email" name="email" class="form-input" value="<?php echo $_SESSION['email']; ?>" <?php echo $editing ? '' : 'readonly'; ?>><br>
        
        <label for="telefono" class="form-label">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" class="form-input" value="<?php echo $_SESSION['telefono']; ?>" <?php echo $editing ? '' : 'readonly'; ?>><br>
        
        <label for="direccion" class="form-label">Dirección:</label>
        <input type="text" id="direccion" name="direccion" class="form-input" value="<?php echo isset($_SESSION['direccion']) ? $_SESSION['direccion'] : ''; ?>" <?php echo $editing ? '' : 'readonly'; ?>><br>
        
        <?php if ($editing): ?>
            <button type="submit" class="btn-enviar">Guardar Datos</button>
        <?php else: ?>
            <a href="?controller=usuario&action=menu_usuario&edit=true" class="btn-enviar">Editar Datos</a>
            <a href="?controller=usuario&action=pedidos_info" class="btn-enviar">Ver Pedidos</a>
            <button type="button" class="btn-enviar" onclick="confirmDelete()">Eliminar Usuario</button>
        <?php endif; ?>
    </form>
</div>

<!-- Confirmación de eliminación de usuario -->
<div id="delete-confirmation" class="confirmation-box">
    <p>¿Estás seguro que deseas eliminar el usuario?</p>
    <button onclick="window.location.href='?controller=usuario&action=eliminar_usuario'" class="btn-enviar">Sí</button>
    <button onclick="document.getElementById('delete-confirmation').style.display='none'" class="btn-enviar">No</button>
</div>

<!-- Script para mostrar la confirmación de eliminación de usuario -->
<script>
function confirmDelete() {
    document.getElementById('delete-confirmation').style.display = 'block';
}
</script>