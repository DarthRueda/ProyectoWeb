<?php
class Usuario {
    public $id;
    public $usuario;
    public $nombre;
    public $apellido;
    public $contrasena;
    public $email;
    public $telefono;
    public $direccion;

    //Construcotr para usuarions creados desde el panel de administracion, sin esto no funciona no se  porque.
    public function __construct($usuario, $nombre, $apellido, $contrasena, $email, $telefono = null, $direccion = null) {
        $this->usuario = $usuario;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->contrasena = $contrasena;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
    }
}
?>
