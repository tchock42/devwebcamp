<?php
namespace Controllers;

use Model\Dia;
use Model\Hora;
use MVC\Router;
use Model\Evento;
use Model\Ponente;
use Model\Categoria;
use Classes\Paginacion;

class EventosController{

    public static function index(Router $router){
        //paginacion
        $pagina_actual = $_GET['page'];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

        if(!$pagina_actual || $pagina_actual < 1){ //si no hay paginacion correcta en el query string
            header('Location: /admin/eventos?page=1');
        }
        $por_pagina = 10; //se configura a 10 registros por pagina
        $total = Evento::total(); //se trae el total de eventos
        $paginacion = new Paginacion($pagina_actual,$por_pagina, $total); // crea una instancia de paginacion
        
        $eventos = Evento::paginar($por_pagina, $paginacion->offset()); //
        //para no usar join se traen los datos con find y se guardan en nuevos atributos
        foreach($eventos as $evento){
            $evento->categoria = Categoria::find($evento->categoria_id);   
            $evento->dia = Dia::find($evento->dia_id);
            $evento->hora = Hora::find($evento->hora_id);
            $evento->ponente = Ponente::find($evento->ponente_id);
        }


        //le pasa la carpeta en views y los datos
        $router->render('admin/eventos/index', [
            'titulo' => 'Conferencias y Workshops', //pasa el titulo
            'eventos' => $eventos, //los eventos
            'paginacion' => $paginacion->paginacion() //
        ]);
    }
    public static function crear( Router $router){
        $alertas = [];
        
        $categorias = Categoria::all();
        $dias = Dia::all('ASC');
        $horas = Hora::all('ASC');
        $evento = new Evento; //crea una instancia vacía

        // debuguear($evento);
        if($_SERVER['REQUEST_METHOD'] ==='POST'){
            $evento->sincronizar($_POST);
            // debuguear($evento);
            $alertas = $evento->validar();

            if(empty($alertas)){
                $resultado = $evento->guardar();
                if($resultado){
                    header('Location: /admin/eventos');
                }
            }
        }

        $router->render('admin/eventos/crear', [
            'titulo' => 'Registrar Eventos',
            'alertas' => $alertas,
            'categorias' => $categorias,
            'dias' => $dias,
            'horas' => $horas,
            'evento' => $evento
        ]);
    }
    public static function editar( Router $router){
        if(!is_admin()){ //revisa si es admin
            header('Location: /login');
        } 
        $alertas = [];

        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT); //realiza el filtrado de id
        if(!$id){
            header('Location: /admin/eventos');
        }
        //importa las categorias, dias, horas para desplegarlas en el formulario
        $categorias = Categoria::all();
        $dias = Dia::all('ASC');
        $horas = Hora::all('ASC');
        $evento = Evento::find($id); //busca el evento por su id
        
        //si no encuentra el evento
        if(!$evento){
            header('Location: /admin/eventos');
        }

        // debuguear($evento);
        if($_SERVER['REQUEST_METHOD'] ==='POST'){
            $evento->sincronizar($_POST);
            // debuguear($evento);
            $alertas = $evento->validar();

            if(empty($alertas)){
                $resultado = $evento->guardar();
                if($resultado){
                    header('Location: /admin/eventos');
                }
            }
        }

        $router->render('admin/eventos/editar', [
            'titulo' => 'Editar el Evento',
            'alertas' => $alertas,
            'categorias' => $categorias,
            'dias' => $dias,
            'horas' => $horas,
            'evento' => $evento
        ]);
    }
    //eliminar evento
    public static function eliminar(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){ //revisa si es admin
                header('Location: /login');
            } 
            $id = $_POST['id'];
            $evento = Evento::find($id);
            if(!isset($evento)){
                header('Location: /admin/ponentes');
            }
            $carpeta_imagenes = '../public/img/speakers'; //selecciona carpeta destino
            unlink($carpeta_imagenes . '/' . $evento->imagen . ".png");
            unlink($carpeta_imagenes . '/' . $evento->imagen . ".webp");
            $resultado = $evento->eliminar(); //elimina el evento de la base de datos
            if($resultado){
                header('Location: /admin/eventos');
            }
        }
    }
}