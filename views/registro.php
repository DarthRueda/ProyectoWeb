<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once ("config/dataBase.php");
    $con = DataBase::connect();
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $telefono = $_POST['telefono'];

    $sql = "INSERT INTO usuarios (nombre, apellido, email, contrasena, telefono) VALUES ('$nombre', '$apellido', '$email', '$password', '$telefono')";
    if ($con->query($sql) === TRUE) {
        echo "Registro exitoso";
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
    $con->close();
}
?>

<form method="POST" action="" class="form-style">
    <label for="nombre" class="form-label">Nombre:</label>
    <input type="text" id="nombre" name="nombre" class="form-input" required><br>
    <label for="apellido" class="form-label">Apellido:</label>
    <input type="text" id="apellido" name="apellido" class="form-input" required><br>
    <label for="email" class="form-label">Correo Electrónico:</label>
    <input type="email" id="email" name="email" class="form-input" required><br>
    <label for="password" class="form-label">Contraseña:</label>
    <input type="password" id="password" name="password" class="form-input" required><br>
    <label for="telefono" class="form-label">Teléfono:</label>
    <input type="text" id="telefono" name="telefono" class="form-input" required><br>
    <button type="submit" class="btn-enviar">Registrar-se</button>

</form>
<a href="index.php?controller=usuario&action=login" class="form-link">¿Ya tienes una cuenta? Inicia sesión</a>
