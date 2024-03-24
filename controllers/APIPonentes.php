<?php
namespace Controllers;

use Model\Ponente;

class APIPonentes{

    public static function index(){
        $ponentes = Ponente::all();
        echo json_encode($ponentes);
    }

    //meotod para obtener solo un registro
    public static function ponente(){
        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if(!$id || $id < 1){
            echo json_encode([]); //si está vacio el id en get o no es valido
            return; //sale del metodo
        }
        $ponente = Ponente::find($id);
        echo json_encode($ponente, JSON_UNESCAPED_SLASHES);
    }
}