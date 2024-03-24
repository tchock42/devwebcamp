<?php
namespace Classes;

class Paginacion{
    public $pagina_actual; //pagina que se muestra actualmente
    public $registros_por_pagina;   //registros por pagina actual
    public $total_registros;    //total de ponentes

    public function __construct($pagina_actual=1, $registros_por_pagina=10,$total_registros=0)
    {
        $this->pagina_actual = (int) $pagina_actual;
        $this->registros_por_pagina = (int) $registros_por_pagina;
        $this->total_registros = (int) $total_registros;
    }

    public function offset(){ //numero de elementos que se deben omitir para realizar la consulta
        return $this->registros_por_pagina * ($this->pagina_actual - 1);
        //pagina_actual=1; return  10 * ( 1 - 1) = 0
        //pagina_actual=2; return 10*(2-1) = 10
        //                 return 10*(3-1) = 20

    }
    public function total_paginas(){
        return ceil($this->total_registros / $this->registros_por_pagina); //redondea hacia arriba
    }
    //pagina anterior
    public function pagina_anterior(){
        $anterior = $this->pagina_actual - 1; //2-1 = 1
        return ($anterior > 0) ? $anterior : false; //evita que anterior sea 0
    }
    public function pagina_siguiente(){
        $siguiente = $this->pagina_actual + 1;
        return ($siguiente <= $this->total_paginas()) ? $siguiente : false;
    }
    public function enlace_anterior(){
        $html = '';
        if($this->pagina_anterior()){ //si tiene un valor válido
            $html .= "<a class=\"paginacion__enlace paginacion__enlace--texto\" href=\"?page={$this->pagina_anterior()}\">&laquo Anterior</a>";
        }
        return $html;
    }
    //desarrolla el html del paginador
    public function enlace_siguiente() {
        $html = '';
        if($this->pagina_siguiente()){ //si tiene un valor válido
            $html .= "<a class=\"paginacion__enlace paginacion__enlace--texto\" href=\"?page={$this->pagina_siguiente()}\">Siguiente &raquo</a>";
        }
        return $html;
    }
    //agrega la funcionalidad de numeros con links a cada paginado, excepto pagina actual
    public function numeros_paginas(){
        $html = '';
        for($i = 1; $i <=$this->total_paginas(); $i++){
            if($i === $this->pagina_actual){
                $html .= "<span class = \"paginacion__enlace paginacion__enlace--actual\">{$i}</span>";
            }else{
                $html .= "<a class = \"paginacion__enlace paginacion__enlace--numero\" href = \"/admin/ponentes?page={$i}\">{$i}</a>";
            }
            
        }
        return $html;
    }

    //imprime la pagina siguiente, anterior y otras paginas
    public function paginacion(){
        $html = '';
        if($this->total_registros > 1){
            $html .= '<div class="paginacion">';
            $html .= $this->enlace_anterior();
            $html .= $this->numeros_paginas();
            $html .= $this->enlace_siguiente();
            $html .= '</div>';
        }
        return $html;
    }
}