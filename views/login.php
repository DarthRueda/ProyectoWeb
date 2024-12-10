<?php
if (session_status() == PHP_SESSION_NONE) { //Comprueba si la sesión está iniciada, si no lo está, la inicia
    session_start();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once ("config/dataBase.php");
    $con = DataBase::connect();
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $sql = "SELECT id_usuario, nombre, apellido, email, telefono, direccion, contrasena FROM usuarios WHERE usuario='$usuario'";
    $result = $con->query($sql);

    //Comprueba si el usuario existe
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['contrasena'])) { //Compara la contraseña introducida con la contraseña encriptada de la base de datos
            $_SESSION['loggedin'] = true;
            $_SESSION['id_usuario'] = $row['id_usuario'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['apellido'] = $row['apellido'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['telefono'] = $row['telefono'];
            $_SESSION['direccion'] = $row['direccion'];
            header("Location: index.php?controller=usuario&action=menu_usuario");
            exit;
        } else {
            echo "Nombre o contraseña incorrectos. Por favor, inténtelo de nuevo o regístrese si no tiene una cuenta.";
        }
    } else {
        echo "Nombre o contraseña incorrectos. Por favor, inténtelo de nuevo o regístrese si no tiene una cuenta.";
    }
    $con->close();
}
?>
<nav aria-label="breadcrumb" class="breadcrumb-container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Iniciar Sesión</li>
    </ol>
</nav>
<form method="POST" action="" class="form-login">
    <label for="usuario" class="form-label">Usuario:</label>
    <input type="text" id="usuario" name="usuario" class="form-input" required><br>
    <label for="password" class="form-label">Contraseña:</label>
    <input type="password" id="password" name="password" class="form-input" required><br>
    <button type="submit" class="btn-enviar">Iniciar Sesión</button>
</form>
<a href="index.php?controller=usuario&action=registro" class="form-link">¿No tienes una cuenta? Regístrate</a>
