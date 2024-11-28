<?php
include_once("models/usuario.php");
include_once("models/usuariosDAO.php");

class usuarioController{
    public function login(){
        $view = "views/login.php";
        include_once 'views/main.php';
    }
    public function registro(){
        $view = "views/registro.php";
        include_once 'views/main.php';
    }
}
?>