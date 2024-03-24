<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}
function pagina_actual($path) : bool{
    return str_contains($_SERVER['PATH_INFO'] ?? '/', $path); //busca $path en $_SERVER
}

function is_auth() : bool{
    if(!isset($_SESSION)){
        session_start();
    }
    return isset($_SESSION['nombre']) && !empty($_SESSION); //si tiene sesion y NO está vacío return true y true
}
function is_admin() : bool{ //revisa si la sesión aactiva es de administrador
    if(!isset($_SESSION)){
        session_start();
    }
    return isset($_SESSION['admin']) && !empty($_SESSION['admin']);
}
function aos_animacion(): void{
    $efectos = ['fade-up', 'fade-down', 'fade-left', 'fade-right', 'flip-left', 'flip-right', 'zoom-in', 'zoom-in-up', 'zoom-in-out', 'zoom-out'];
    $efecto = array_rand($efectos, 1); //retorna una posicion aleatoria del arreglo $efectos, puede ser del 0 al 9
    echo ' data-aos="' . $efectos[$efecto] . '" '; //imprime el atributo y efecto en el lugar que sea llamada la funcion
}
