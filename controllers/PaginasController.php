<?php

namespace Controllers;
use Model\Dia;
use Model\Hora;
use MVC\Router;
use Model\Evento;
use Model\Ponente;
use Model\Categoria;

class PaginasController{
    public static function index(Router $router){
        
        $eventos = Evento::ordenar('hora_id', 'ASC'); //las horas mas nuevas van al final
        
        $eventos_formateados = []; //declara un arreglo vacío
        foreach($eventos as $evento){ //ordena los eventos en un arreglo de 4 indices
            //para cada evento se agrega su categoria, dia, hora y ponente
            $evento->categoria = Categoria::find($evento->categoria_id);   
            $evento->dia = Dia::find($evento->dia_id);
            $evento->hora = Hora::find($evento->hora_id);
            $evento->ponente = Ponente::find($evento->ponente_id);

            if($evento->dia_id === "1" && $evento->categoria_id === "1"){ //conferencias en viernes
                $eventos_formateados['conferencias_v'][] = $evento;
            }
            if($evento->dia_id ==="2" && $evento->categoria_id === "1") { //conferencias en sabado
                $eventos_formateados['conferencias_s'][] = $evento;
            }
            if($evento->dia_id ==="1" && $evento->categoria_id === "2") { //workshops y viernes
                $eventos_formateados['workshops_v'][] = $evento;
            }
            if($evento->dia_id ==="2" && $evento->categoria_id === "2") { //workshops en sabado
                $eventos_formateados['workshops_s'][] = $evento;
            }
        }
        //obtener el total de cada bloque
        $ponentes_total = Ponente::total();
        $conferencias_total = Evento::total('categoria_id', 1);
        $workshops_total = Evento::total('categoria_id', 2);
        //obtener todos los ponentes
        $ponentes = Ponente::all();

        $router->render('paginas/index', [ //ubicacion en disco
            'titulo' => 'Inicio',
            'eventos' => $eventos_formateados,
            'ponentes_total' => $ponentes_total,
            'conferencias_total' => $conferencias_total,
            'workshops_total' => $workshops_total,
            'ponentes' => $ponentes
        ]);
    }


    public static function evento(Router $router){
        

        $router->render('paginas/devwebcamp', [
            'titulo' => 'Sobre DevWebCamp'
        ]);
    }

    public static function paquetes(Router $router){
        

        $router->render('paginas/paquetes', [
            'titulo' => 'Paquetes de DevWebCamp'
        ]);
    }
    public static function conferencias(Router $router){

        $eventos = Evento::ordenar('hora_id', 'ASC'); //las horas mas nuevas van al final
        
        $eventos_formateados = []; //declara un arreglo vacío
        foreach($eventos as $evento){ //ordena los eventos en un arreglo de 4 indices
            //para cada evento se agrega su categoria, dia, hora y ponente
            $evento->categoria = Categoria::find($evento->categoria_id);   
            $evento->dia = Dia::find($evento->dia_id);
            $evento->hora = Hora::find($evento->hora_id);
            $evento->ponente = Ponente::find($evento->ponente_id);
            if($evento->dia_id === "1" && $evento->categoria_id === "1"){ //conferencias en viernes
                $eventos_formateados['conferencias_v'][] = $evento;
            }
            if($evento->dia_id ==="2" && $evento->categoria_id === "1") { //conferencias en sabado
                $eventos_formateados['conferencias_s'][] = $evento;
            }
            if($evento->dia_id ==="1" && $evento->categoria_id === "2") { //workshops y viernes
                $eventos_formateados['workshops_v'][] = $evento;
            }
            if($evento->dia_id ==="2" && $evento->categoria_id === "2") { //workshops en sabado
                $eventos_formateados['workshops_s'][] = $evento;
            }
        }
        $router->render('paginas/conferencias', [ //ubicacion en disco
            'titulo' => 'Conferencias & Workshops',
            'eventos' => $eventos_formateados
        ]);
    }
    public static function error(Router $router){

        $router->render('paginas/error', [
            'titulo' => 'Página no encontrada'
        ]);
    }
}