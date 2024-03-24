<?php
namespace Model;

//clase de creación de registros de paquetes adquiridos
class Regalo extends ActiveRecord{
    protected static $tabla = 'regalos'; //tabla consultada en la db
    protected static $columnasDB = ['id', 'nombre'];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }
}