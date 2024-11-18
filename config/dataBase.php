<?php

class DataBase{
    public static function connect($host = 'localhost', $user = 'root', $pass = 'Asdqwe!23', $db = 'BD_FASTFORMULA', $port = 3307){
        $con = new mysqli($host, $user, $pass, $db, $port);
        if ($con === false) {
            die("ERROR!!: NO te puedes conectar. " . mysqli_connect_error());
        };
        return $con;
    }
}