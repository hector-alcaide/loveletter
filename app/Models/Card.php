<?php

namespace App\Models;

class Card
{
    public $idCarta;
    public $nivel;
    public $titulo;
//    public $descripcion;
    public $imagen;

    public function __construct($idCarta, $nivel, $titulo, $imagen) {
        $this->idCarta = $idCarta;
        $this->nivel = $nivel;
        $this->titulo = $titulo;
//        $this->descripcion = $descripcion;
        $this->imagen = $imagen;
    }
}
