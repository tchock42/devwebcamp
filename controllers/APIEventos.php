<?php
namespace Controllers;

use Model\EventoHorario;

class APIEventos{

    public static function index(){

        $dia_id = $_GET['dia_id'];
        $categoria_id = $_GET['categoria_id'];

        $dia_id = filter_var($dia_id, FILTER_VALIDATE_INT);
        $categoria_id = filter_var($categoria_id, FILTER_VALIDATE_INT);
        
        if(!$dia_id || !$categoria_id){  //si no encuentra dia_id o categoria_id

            return; //sale del metodo
        }
        
        //Consultar la base de datos
        //se le pasan los keys porque son el nombre de la columna, se pasan las variables anteriores del GET
        $eventos = EventoHorario::whereArray(['dia_id' => $dia_id, 'categoria_id' => $categoria_id]) ?? [];
        echo json_encode($eventos); //transforma en json la consulta de dia_id y categoria_id
    }
}