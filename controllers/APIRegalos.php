<?php
namespace Controllers;

use Model\Regalo;
use Model\Registro;

class APIRegalos{

    public static function index(){
        if(!is_admin()){
            echo json_encode([]);
            return;
        }
        $regalos = Regalo::all(); //se traen todos los regalos 
        // debuguear($regalos);
        foreach($regalos as $regalo){ //regalo1, regalo2 ... regalo9
            $regalo->total = Registro::totalArray(['regalo_id' => $regalo->id, 'paquete_id' => "1"]);
        }
        echo json_encode($regalos);
        return;
    }

}