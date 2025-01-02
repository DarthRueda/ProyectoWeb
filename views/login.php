<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAST FORMULA | Login</title>
</head>
<body>
<nav aria-label="breadcrumb" class="breadcrumb-container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Iniciar Sesión</li>
    </ol>
</nav>
<form method="POST" action="?controller=usuario&action=login" class="form-login">
    <label for="usuario" class="form-label">Usuario:</label>
    <input type="text" id="usuario" name="usuario" class="form-input" required><br>
    <label for="password" class="form-label">Contraseña:</label>
    <input type="password" id="password" name="password" class="form-input" required><br>
    <button type="submit" class="btn-enviar">Iniciar Sesión</button>
</form>
<a href="index.php?controller=usuario&action=registro" class="form-link">¿No tienes una cuenta? Regístrate</a>
</body>
</html>
