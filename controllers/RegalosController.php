<?php
namespace Controllers;

use MVC\Router;

class RegalosController{

    public static function index(Router $router){
        //le pasa la carpeta en views y los datos
        $router->render('admin/regalos/index', [
            'titulo' => 'Regalos'
        ]);
    }
}