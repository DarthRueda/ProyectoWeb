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

    // Esta funcion permite obtener todos los usuarios de la base de datos
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

    // Esta funcion permite obtener un usuario por su ID
    public static function getUserByUsername($usuario) {
        $con = DataBase::connect();
        $query = "SELECT id_usuario, nombre, apellido, email, telefono, direccion, contrasena FROM usuarios WHERE usuario = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        $con->close();
        return $user;
    }

    // Esta funcion permite verificar si un usuario es administrador
    public static function isAdmin($id_usuario) {
        $con = DataBase::connect();
        $query = "SELECT administrador FROM usuarios WHERE id_usuario = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id_usuario);
        $stmt->execute();
        $stmt->bind_result($administrador);
        $stmt->fetch();
        $stmt->close();
        $con->close();
        return $administrador == 1;
    }
}
?>
