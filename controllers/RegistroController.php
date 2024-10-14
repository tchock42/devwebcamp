<?php 

namespace Controllers;

use Model\Dia;
use Model\Hora;
use MVC\Router;
use Model\Evento;
use Model\Paquete;
use Model\Ponente;
use Model\Usuario;
use Model\Registro;
use Model\Categoria;
use Model\EventosRegistros;
use Model\Regalo;

class RegistroController{

    //metodo para desplegar seleccionar paquete /finalizar-registro
    public static function crear(Router $router){
        //verificar que el usuario esté autenticado 
        if(!is_auth()){
            header('Location: /');
            return;
        }
        //verificar si el usuario ya está  registrado
        $registro = Registro::where('usuario_id', $_SESSION['id']);
        $registroFinalizado = EventosRegistros::where('registro_id', $registro->id);//si ya se eligieron paquetes

        if(!isset($registroFinalizado) && isset($registro)){    //si ya pagó pero no ha registrado sus eventos
            header('Location: /finalizar-registro/conferencias');
            return;
        }
        // debuguear($registroFinalizado);
        if( isset($registro) && $registro->paquete_id === "1" ){ //si el usuario ya pagó
            header('Location: /finalizar-registro/conferencias');
            return;
        }
        if( isset($registro) && ($registro->paquete_id === "3" || $registro->paquete_id === "2")){
            header('Location: /boleto?id=' . urlencode($registro->token));
            return;
        }

        //en la carpeta registro
        $router->render('registro/crear', [
            'titulo' => 'Finalizar Registro'
        ]);
    }
    //metodo el boton de registro gratis
    public static function gratis(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_auth()){
                header('Location: /login');
                return;
            }
            //verificar si el usuario ya está  registrado
            $registro = Registro::where('usuario_id', $_SESSION['id']); //se busca en la DB
            if(isset($registro) && $registro->paquete_id === "3"){ //si ya existe un registro
                header('Location: /boleto?id=' . urlencode($registro->token)); //se redirecciona al boleto virtual
                return;
            }
            //crear el boleto virtual
            $token = substr(md5(uniqid(rand(), true)), 0, 8); //cre un token de  carateres random
            
            //crear un arreglo asoc con los datos del registro (no objeto)
            $datos = array( //lleva los 4 datos (id no) de la clase registro
                'paquete_id' => 3, //este es el método gratis
                'pago_id' => '', //este paquete no tiene pago
                'token' => $token, //token generado antes
                'usuario_id' => $_SESSION['id'] //el id del usuario guardado en la sesion
            );
            $registro = new Registro($datos); //crea el objeto con el arreglo anterior
            // debuguear($registro);
            $resultado = $registro->guardar(); /* Guarda*/ 
            if($resultado){
                header('Location: /boleto?id=' . urlencode($registro->token));
                return;
            }

        }
    }
    //API metodo para boton de pagar pase presencial, guardar el registro en la DB
    public static function pagar(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_auth()){
                header('Location: /login');
                return;
            }
            //validar que POSt no esté vacío | espero la información que manda paypal
            if(empty($_POST)){
                echo json_encode([]); //json vacío no se modifica la base de datos
                return;
            }
            //crear la instancia registro
            $datos = $_POST; //datos enviados por el formdata
            $token = substr(md5(uniqid(rand(), true)), 0, 8); //cre un token de  carateres random
            $datos['token'] = $token;
            $datos['usuario_id'] =  $_SESSION['id'];

            //guardar el registro
            try { //si no se ejecuta el codigo siguiente, pasa al catch
                $registro = new Registro($datos); //crea el objeto con el arreglo anterior
                $resultado = $registro->guardar();    
                echo json_encode($resultado);           //retorna la respuesta a crear.php
            } catch (\Throwable $th) {
                echo json_encode([
                    'resultado' => 'error'  //aqui se llega si hubo un error en el pago
                ]);
            }
        }
    }
    //metodo para pagina con boleto virtual
    public static function boleto(Router $router){
        //validar la url por su token
        $id = $_GET['id'];
        if(!$id || strlen($id) !== 8){
            header('Location: /');
            return;
        }
        $registro = Registro::where('token', $id); //busca el token en la tabla registro. columna token
        if(!$registro){
            header('Location: /');  //si no, redirecciona a '/'
            return;
        }
        //llenar el objeto con informacion de Usuario y Paquete
        $registro->usuario = Usuario::find($registro->usuario_id); //busca en usuario con su id
        $registro->paquete = Paquete::find($registro->paquete_id); //se busca el paquete con el id de paquete
        // debuguear($registro);
        //en la carpeta registro
        $router->render('registro/boleto', [
            'titulo' => 'Asistencia a DevWebCamp',
            'registro' => $registro
        ]);
    }
    //seleccion de conferencias y registro de pago
    public static function conferencias(Router $router){
        
        //Validar que esté sesion abierta 
        if(!is_auth()){
            header('Location: /login');
            return;
        }
        //validar que el usuario tenga el plan presencial
        $usuario_id = $_SESSION['id']; //id del usuario
        $registro = Registro::where('usuario_id', $usuario_id);

        //crear una instancia de EventosRegistrados para saber si ya se finalizó el registro
        $registroFinalizado = EventosRegistros::where('registro_id', $registro->id);    //es solo el primer elemento de varios posibles eventos registrados
        // debuguear($registroFinalizado);
        if(isset($registro) && ($registro->paquete_id === "2" || $registro->paquete_id === "3" )) {
            header('Location: /boleto?id=' . urlencode($registro->token));
            return;
        }
        if($registro->paquete_id !== "1") {
            header('Location: /');
            return;
        }
        if($registro->paquete_id === "3") {
            header('Location: /boleto?id=' . urlencode($registro->token));
            return;
        }
        //Redireccionar a boleto virtual en caso de haber finalizado su registro
        if(isset($registroFinalizado)){
            header('Location: /boleto?id=' . urlencode($registro->token));
            return;
        }        
        if(is_null($registro->pago_id)){
            header('Location: /'); //si no paga, se manda a inicio
            return;
        }
        
        //se categorizan los eventos para desplegarlos
        $eventos = Evento::ordenar('hora_id', 'ASC');
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
        $regalos = Regalo::all('ASC');

        //Manejando el registro mediante $_POST. Los datos se mandan con formdata desde js
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // debuguear($_POST);
            //Revisar que el usuario esté autenticado
            if(!is_auth()){
                header('Location: /login');
            }
            $eventos = explode(',', $_POST['eventos']);
            // debuguear($eventos);
            if(empty($eventos)){ //si de alguna manera se manda el post['evento']
                echo json_encode(['resultado' => false]);
                return; //sale del método
            }
            //obtener el registro de usuario
            $registro = Registro::where('usuario_id', $_SESSION['id']); //se busca al usuario por su id
            // debuguear($registro); 
            if(!isset($registro) || $registro->paquete_id !== "1"){ //si la consulta está vacía o hay un intruso con paquete diferente
                echo json_encode(['resultado' => false]); //retorna false
                return; //sale del método
            }
            $eventos_array = [];
            //validar la disponibilidad de los eventos seleccionados
            foreach($eventos as $evento_id){ //se usa cada id de evento
                $evento = Evento::find($evento_id); //se crea la instancia de $evento
                // debuguear($evento->disponibles);

                //Comprobar que el evento exista y tenga disponibilidad
                if(!isset($evento) || $evento->disponibles === "0"){ 
                    echo json_encode(['resultado' => false]);
                    return;
                }
                
                $eventos_array[] = $evento; //se agrega al final del evento
            }
            //por cada evento selesccionado...
            foreach($eventos_array as $evento){
                //reduce el numero de disponibles
                $evento->disponibles -= 1; 
                
                $evento->guardar(); /* Guarda*/ 

                //almacenar el registro
                $datos = [
                    'evento_id' => (int) $evento->id, // el id del evento seleccionado
                    'registro_id' => (int) $registro->id //el id del registro del paquete del usuario
                ];
                $registro_usuario = new EventosRegistros($datos);
                
                $registro_usuario->guardar(); /* Guarda*/ 
            }

            // Almacenar el regalo
            $registro->sincronizar(['regalo_id' => $_POST['regalo_id']]);
            
            $resultado = $registro->guardar(); /* Guarda*/ 
            // debuguear($resultado);
            if($resultado){
                echo json_encode([
                    'resultado' => $resultado,
                    'token' => $registro->token
                ]);
            }else{
                echo json_encode(['resultado' => false]);
            }
            return;
        }
        //en la carpeta registro
        $router->render('registro/conferencias', [
            'titulo' => 'Elige Workshops y Conferencias',
            'eventos' => $eventos_formateados,
            'regalos' => $regalos
        ]);
    }
}
