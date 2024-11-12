<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

/*Cambiar por la conexión a la base de datos*/
/*Estos datos son de NO es una base de datos, solo es un arreglo de PHP para un ejemplo de API*/
$users = [
    ["id" => 1, "name" => "Lewis Hamilton", "team" => "Mercedes"],
    ["id" => 2, "name" => "Max Verstappen", "team" => "Red Bull Racing"],
    ["id" => 3, "name" => "Charles Leclerc", "team" => "Ferrari"],
    ["id" => 4, "name" => "Pierre Gasly", "team" => "Alpine"],
    ["id" => 5, "name" => "Fernando Alonso", "team" => "Aston Martin"]
];

$metodo = $_SERVER["REQUEST_METHOD"];

switch($metodo){
    case 'GET':
        if (isset($_GET["id"])){
            $existe = false;
            foreach($users as $user){
                if ($user["id"] == $_GET["id"]){
                    echo json_encode([
                        "estado" => "Exito",
                        "data" => $user
                    ]);
                    $existe = true;
                    break;
                }
            }
            if (!$existe){
                http_response_code(404);
                echo json_encode([
                    "estado" => "Error",
                    "data" => "Usuario no encontrado"
                ]);
            }
        }else{
            echo json_encode([
                "estado" => "Exito",
                "data" => $users
            ]);
        }
    break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        
        array_push($users, 
        [
            "id" => count($users) + 1,
            "name" => $data["name"],
            "team" => $data["team"]
        ]);

        echo json_encode([
            "estado" => "Exito",
            "data" => "Usuario agregado con exito"
        ]);
    break;
}