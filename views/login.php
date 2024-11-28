<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once ("config/dataBase.php");
    $con = DataBase::connect();
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];

    $sql = "SELECT id_usuario FROM usuarios WHERE nombre='$nombre' AND contrasena='$password'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        echo "Inicio de sesión exitoso";
    } else {
        echo "Nombre o contraseña incorrectos. Por favor, inténtelo de nuevo o regístrese si no tiene una cuenta.";
    }
    $con->close();
}
?>

<form method="POST" action="" class="form-style">
    <label for="nombre" class="form-label">Nombre:</label>
    <input type="text" id="nombre" name="nombre" class="form-input" required><br>
    <label for="password" class="form-label">Contraseña:</label>
    <input type="password" id="password" name="password" class="form-input" required><br>
    <button type="submit" class="btn-enviar">Iniciar Sesión</button>
</form>
<a href="index.php?controller=usuario&action=registro" class="form-link">¿No tienes una cuenta? Regístrate</a>
