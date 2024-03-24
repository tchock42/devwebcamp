<?php
namespace Controllers;

use MVC\Router;
use Model\Paquete;
use Model\Usuario;
use Model\Registro;
use Classes\Paginacion;

class RegistradosController{

    public static function index(Router $router){
        //validar sesion
        if(!is_admin()){
            header('Location: /login');
        }
        $pagina_actual = $_GET['page'];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

        if(!$pagina_actual || $pagina_actual < 1){
            header('Location: /admin/registrados?page=1');
        }
        $registros_por_pagina = 10;
        $total = Registro::total(); //calcula el total de registros presencial/virtual/gratis
        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total); //crea una instancia de paginacion

        if($paginacion->total_paginas() < $pagina_actual){  //total de paginas(1) < 1
            header('Location: /admin/registrados?page=1'); //dirige a pagina 1
        }
        //genera un listado limitado por $registro_por_pagina saltandose el offset (10, 20 30) de forma descendente
        $registros = Registro::paginar($registros_por_pagina, $paginacion->offset());
        foreach($registros as $registro){ //busca en cada registro
            $registro->usuario = Usuario::find($registro->usuario_id); // usuario por el id de usuario
            $registro->paquete = Paquete::find($registro->paquete_id); // paquete por el id de paquete
        }
        
        $router->render('admin/registrados/index', [
            'titulo' => 'Usuarios Registrados',
            'registros' => $registros,
            'paginacion' => $paginacion->paginacion() //metodo que imprime los botones del paginador
        ]);
    }
}