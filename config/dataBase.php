<?php

class DataBase{ // Clase para la conexión a la base de datos
    public static function connect($host = 'sql101.infinityfree.com', $user = 'if0_38038387', $pass = 'ToppatClan16', $db = 'if0_38038387_bd_fastformula', $port = 3306){ // Método para la conexión a la base de datos con los datos por defecto
        $con = new mysqli($host, $user, $pass, $db, $port);
        if ($con === false) {
            die("ERROR!!: NO te puedes conectar. " . mysqli_connect_error());
        };
        $con->set_charset("utf8mb4");
        return $con;
    }
}