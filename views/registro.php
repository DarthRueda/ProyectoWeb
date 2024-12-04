<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once ("config/dataBase.php");
    $con = DataBase::connect();
    $usuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); //Encriptador
    $telefono = $_POST['telefono'];

    $sql = "INSERT INTO usuarios (usuario, nombre, apellido, email, contrasena, telefono) VALUES ('$usuario', '$nombre', '$apellido', '$email', '$password', '$telefono')";
    if ($con->query($sql) === TRUE) {
        $_SESSION['loggedin'] = true;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellido'] = $apellido;
        $_SESSION['email'] = $email;
        $_SESSION['telefono'] = $telefono;
        echo "<div id='registro-exitoso' style='position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: green; color: white; padding: 20px; font-size: 24px; text-align: center; border-radius: 10px;'>Registro exitoso</div>";
        echo "<script>setTimeout(function() { document.getElementById('registro-exitoso').style.display = 'none'; }, 3000);</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
    $con->close();
}

// Recoger datos del formulario si se ha enviado previamente
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$apellido = isset($_GET['apellido']) ? $_GET['apellido'] : '';
$email = isset($_GET['email']) ? $_GET['email'] : '';
?>

<nav aria-label="breadcrumb" class="breadcrumb-container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Registro</li>
    </ol>
</nav>

<form method="POST" action="" class="form-registro">
    <label for="usuario" class="form-label">Usuario:</label>
    <input type="text" id="usuario" name="usuario" class="form-input" required><br>
    <label for="nombre" class="form-label">Nombre:</label>
    <input type="text" id="nombre" name="nombre" class="form-input" value="<?php echo $nombre; ?>" required><br>
    <label for="apellido" class="form-label">Apellido:</label>
    <input type="text" id="apellido" name="apellido" class="form-input" value="<?php echo $apellido; ?>" required><br>
    <label for="email" class="form-label">Correo Electrónico:</label>
    <input type="email" id="email" name="email" class="form-input" value="<?php echo $email; ?>" required><br>
    <label for="password" class="form-label">Contraseña:</label>
    <input type="password" id="password" name="password" class="form-input" required><br>
    <label for="telefono" class="form-label">Teléfono:</label>
    <input type="text" id="telefono" name="telefono" class="form-input" required><br>
    <button type="submit" class="btn-enviar">Registrar-se</button>

</form>
<a href="index.php?controller=usuario&action=login" class="form-link">¿Ya tienes una cuenta? Inicia sesión</a>
