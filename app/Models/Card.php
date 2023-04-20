<?php

namespace App\Models;

class Card
{
    public $idCarta;
    public $nivel;
    public $carta;
//    public $descripcion;
    public $imagen;

    public function __construct($idCarta, $nivel, $carta, $imagen) {
        $this->idCarta = $idCarta;
        $this->nivel = $nivel;
        $this->carta = $carta;
//        $this->descripcion = $descripcion;
        $this->imagen = $imagen;
    }
}
