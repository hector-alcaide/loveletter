<?php

namespace App\Models;

class Card
{
    public $idCard;
    public $level;
    public $title;
    public $image;

    public function __construct($idCard, $level, $title, $image) {
        $this->idCard = $idCard;
        $this->level = $level;
        $this->title = $title;
        $this->image = $image;
    }
}
