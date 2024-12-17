<?php
include_once __DIR__ . '/../models/usuario.php';
include_once __DIR__ . '/../config/dataBase.php';

class UsuariosDAO {
    // Esta funcion permite insertar un usuario en la base de datos
    public static function insert(Usuario $usuario) {
        $con = DataBase::connect();
        $sql = "INSERT INTO usuarios (usuario, nombre, apellido, email, contrasena, telefono, direccion) VALUES ('$usuario->usuario', '$usuario->nombre', '$usuario->apellido', '$usuario->email', '$usuario->contrasena', '$usuario->telefono', '$usuario->direccion')";
        $con->query($sql);
        $con->close();
    }

    // Esta funcion permite validar el login de un usuario
    public static function validateLogin($usuario, $password) {
        $con = DataBase::connect();
        $sql = "SELECT id_usuario FROM usuarios WHERE usuario='$usuario' AND contrasena='$password'"; // Selecciona el id del usuario que tenga el usuario y contraseña que se le pasa
        $result = $con->query($sql);
        $con->close();
        return $result->num_rows > 0;
    }

    public static function getAll() {
        $con = DataBase::connect();
        $sql = "SELECT * FROM usuarios";
        $result = $con->query($sql);
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
        $con->close();
        return $usuarios;
    }
}
?>
