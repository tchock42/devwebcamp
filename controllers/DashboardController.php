<?php
namespace Controllers;

use Model\Evento;
use MVC\Router;
use Model\Usuario;
use Model\Registro;

class DashboardController{

    public static function index(Router $router){
        // debuguear(is_admin());
        if(!is_admin()){ //revisa si es admin
            header('Location: /login');
        }
        //obtener los ultimos 5 registros
        $registros = Registro::get(5);
        foreach($registros as $registro){
            $registro->usuario = Usuario::find($registro->usuario_id);
        }

        //Clacular los ingresos 
        $virtuales = Registro::total('paquete_id', 2); //trae todos los registros con id = 2
        $presenciales = Registro::total('paquete_id', 1);
        $ingresos = ($virtuales * 46.41) + ($presenciales * 189.54);

        //obtener eventos con mas y menos lugares disponibles
        $menos_lugares = Evento::ordenarLimite('disponibles', 'ASC', 5); //columa disponibles, ascendentes y 5
        $mas_lugares = Evento::ordenarLimite('disponibles', 'DESC', 5); //columa disponibles, ascendentes y 5
        // debuguear($mas_lugares);
        //le pasa la carpeta en views y los datos
        $router->render('admin/dashboard/index', [
            'titulo' => 'Panel de Administración',
            'registros' => $registros,
            'ingresos' => $ingresos,
            'menos_disponibles' => $menos_lugares,
            'mas_disponibles' => $mas_lugares
        ]);
    }
}