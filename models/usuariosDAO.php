<?php
include_once ("models/usuario.php");
include_once ("config/dataBase.php");

class UsuariosDAO {
    public static function insert(Usuario $usuario) {
        $con = DataBase::connect();
        $sql = "INSERT INTO usuarios (nombre, apellido, email, contrasena, telefono, direccion) VALUES ('$usuario->nombre', '$usuario->apellido', '$usuario->email', '$usuario->contrasena', '$usuario->telefono', '$usuario->direccion')";
        $con->query($sql);
        $con->close();
    }

    public static function validateLogin($nombre, $password) {
        $con = DataBase::connect();
        $sql = "SELECT id_usuario FROM usuarios WHERE nombre='$nombre' AND contrasena='$password'";
        $result = $con->query($sql);
        $con->close();
        return $result->num_rows > 0;
    }
}
?>
