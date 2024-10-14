<?php
namespace Controllers;

use MVC\Router;
use Model\Ponente;
use Classes\Paginacion;
use Intervention\Image\ImageManagerStatic as Image;

class PonentesController{

    public static function index(Router $router){
        if(!is_admin()){ //revisa si es admin
            header('Location: /login');
        } 
        /*Paginacion         */
        //lee el get y lo filtra
        $pagina_actual = filter_var( $_GET['page'], FILTER_VALIDATE_INT );
        if(!$pagina_actual || $pagina_actual < 0){ //si no es valido o es menor a 0
            header('Location: /admin/ponentes?page=1');
        } 

        $registros_por_pagina = 4;
        $total = Ponente::total(); //retorna el numero de registros de la tabla ponentes
        //crea una instancia de paginacin
        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total);
        if($paginacion->total_paginas() < $pagina_actual){ //si se quiere a la pagina 100
            header('Location: /admin/ponentes?page=1');
        }
        $ponentes = Ponente::paginar($registros_por_pagina, $paginacion->offset());  //trae los ponentes para imprimirlos
        // debuguear($ponentes);
        if(!is_admin()){ //revisa si es admin
            header('Location: /login');
        }
        //le pasa la carpeta en views y los datos
        $router->render('admin/ponentes/index', [
            'titulo' => 'Ponentes / Conferencistas',
            'ponentes' => $ponentes,
            'paginacion' => $paginacion->paginacion()
        ]);
    }
    public static function crear(Router $router){
        if(!is_admin()){ //revisa si es admin
            header('Location: /login');
        } 

        $alertas=[];
        $ponente = new Ponente;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){ //revisa si es admin
                header('Location: /login');
            } 
            //detectar la imagen
            if($_FILES['imagen']['tmp_name']){
                $carpeta_imagenes = '../public/img/speakers'; //selecciona carpeta destino
                //Crear la carpeta si no existe
                if(!is_dir($carpeta_imagenes)){
                    mkdir($carpeta_imagenes, 0755, true);
                }
                //genera un resize y corte,calidad de 80, en imagen png y webp. no guarda aun
                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('png', 80); //toma el temporal de $_FILES la imagen
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('webp', 80); //toma el temporal de $_FILES la imagen

                //genera el nombre de la imagen
                $nombre_imagen = md5( uniqid( rand(), true) );
                //agrega el nombre de la imagen al post
                $_POST['imagen'] = $nombre_imagen;

            }
            //convierte el arreglo de redes en un string (json)
            $_POST['redes'] = json_encode($_POST['redes'], JSON_UNESCAPED_SLASHES);

            //sincroniza el post con la instancia
            $ponente->sincronizar($_POST);
            
            //valida y crea las alertas
            $alertas = $ponente->validar();

            //guardar el registro
            if(empty($alertas)){ //si no hay alertas
                //guardar las imagenes -> ubicacion + / + nombre + extension
                $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png");
                $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . ".webp");

                $resultado = $ponente->guardar();

                if($resultado){
                    header('Location: /admin/ponentes');
                }
            }
        }

        //le pasa la carpeta en views y los datos
        $router->render('admin/ponentes/crear', [
            'titulo' => 'Registrar Ponente',
            'alertas' => $alertas,
            'ponente' => $ponente,
            'redes' => json_decode($ponente->redes)
        ]);
    }

    public static function editar(Router $router){
        if(!is_admin()){ //revisa si es admin
            header('Location: /login');
        }
        $alertas = [];
        
        //validar el id
        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if(!$id){
            header('Location: /admin/ponentes');
        }
        //obtener el ponente a editar
        $ponente = Ponente::find($id);

        if(!$ponente){
            header('Location: /admin/ponentes');
        }
        $ponente->imagen_actual = $ponente->imagen; //crea imagenactual para desplegarla en actualizar

        //transforma el json string de redes en un objeto
        $redes = json_decode($ponente->redes);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){ //si se presiona guardar cambios
            if(!is_admin()){ //revisa si es admin
                header('Location: /login');
            } 
            //detectar la imagen si se quiere actualizar
            if($_FILES['imagen']['tmp_name']){ //si existe una imagen en el $_FILES cargada por el usuario

                $carpeta_imagenes = '../public/img/speakers'; //selecciona carpeta destino

                //eliminar la imagen anterior
                unlink($carpeta_imagenes . '/' . $ponente->imagen_actual . ".png");
                unlink($carpeta_imagenes . '/' . $ponente->imagen_actual . ".webp");

                //Crear la carpeta si no existe
                if(!is_dir($carpeta_imagenes)){
                    mkdir($carpeta_imagenes, 0755, true);
                }
                //genera un resize y corte,calidad de 80, en imagen png y webp. no guarda aun
                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('png', 80); //toma el temporal de $_FILES la imagen
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('webp', 80); //toma el temporal de $_FILES la imagen

                //genera el nombre de la imagen
                $nombre_imagen = md5( uniqid( rand(), true) );
                //agrega el nombre de la imagen al post
                $_POST['imagen'] = $nombre_imagen;

            }else{ //si no hay una imagen para actualizar la imagen_actual
                $_POST['imagen'] = $ponente->imagen_actual; //se vuelve a asignar la imagen original al post
            }

            //convierte el arreglo de redes en un string (json)
            $_POST['redes'] = json_encode($_POST['redes'], JSON_UNESCAPED_SLASHES);
            $ponente->sincronizar($_POST); 

            $alertas = $ponente->validar();

            if(empty($alertas)){ //si no hay alertas
                if(isset($nombre_imagen)){ //si se creó el nombre de la imagen
                    //se tienen que guardar fisicamente en carpeta speakers
                    $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png");
                    $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . ".webp");
                }
                $resultado = $ponente->guardar();
                if($resultado){
                    header('Location: /admin/ponentes');
                }
            }
        }
        //le pasa la carpeta en views y los datos
        $router->render('admin/ponentes/editar', [
            'titulo' => 'Actualizar Ponente',
            'alertas' => $alertas,
            'ponente' => $ponente ?? null,
            'redes' => $redes
        ]);
    }
    public static function eliminar(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){ //revisa si es admin
                header('Location: /login');
            } 
            $id = $_POST['id'];
            $ponente = Ponente::find($id);
            if(!isset($ponente)){
                header('Location: /admin/ponentes');
            }
            $carpeta_imagenes = '../public/img/speakers'; //selecciona carpeta destino
            unlink($carpeta_imagenes . '/' . $ponente->imagen . ".png");
            unlink($carpeta_imagenes . '/' . $ponente->imagen . ".webp");
            $resultado = $ponente->eliminar(); //elimina el ponente de la base de datos
            if($resultado){
                header('Location: /admin/ponentes');
            }
        }
    }
}