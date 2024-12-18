<?php
include_once __DIR__ . '/../models/usuario.php';
include_once __DIR__ . '/../config/dataBase.php';

class UsuariosDAO {
    // Esta funcion permite insertar un usuario en la base de datos
    public static function insert(Usuario $usuario) {
        $con = DataBase::connect();
        $query = "INSERT INTO usuarios (usuario, nombre, apellido, email, contrasena, telefono) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssssss", $usuario->usuario, $usuario->nombre, $usuario->apellido, $usuario->email, $usuario->contrasena, $usuario->telefono);
        $result = $stmt->execute();
        $stmt->close();
        $con->close();
        return $result;
    }

    // Esta funcion permite validar el login de un usuario
    public static function validateLogin($usuario, $password) {
        $con = DataBase::connect();
        $sql = "SELECT id_usuario FROM usuarios WHERE usuario='$usuario' AND contrasena='$password'";
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
