<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    use HasFactory;
    public $cartas;

    public function __construct() {
        $this->cartas = [
            [new Card(1, 0, 'Espía', '/resources/images/cartas/espia.png')],
            [new Card(2, 0, 'Espía', '/resources/images/cartas/espia.png')],
            [new Card(3, 1, 'Guardia', '/resources/images/cartas/guardia.png')],
            [new Card(4, 1, 'Guardia', '/resources/images/cartas/guardia.png')],
            [new Card(5, 1, 'Guardia', '/resources/images/cartas/guardia.png')],
            [new Card(6, 1, 'Guardia', '/resources/images/cartas/guardia.png')],
            [new Card(7, 1, 'Guardia', '/resources/images/cartas/guardia.png')],
            [new Card(8, 1, 'Guardia', '/resources/images/cartas/guardia.png')],
            [new Card(9, 2, 'Sacerdote', '/resources/images/cartas/sacerdote.png')],
            [new Card(10, 2, 'Sacerdote', '/resources/images/cartas/sacerdote.png')],
            [new Card(11, 3, 'Barón', '/resources/images/cartas/baron.png')],
            [new Card(12, 3, 'Barón', '/resources/images/cartas/baron.png')],
            [new Card(13, 4, 'Doncella', '/resources/images/cartas/doncella.png')],
            [new Card(14, 4, 'Doncella', '/resources/images/cartas/doncella.png')],
            [new Card(15, 5, 'Príncipe', '/resources/images/cartas/principe.png')],
            [new Card(16, 5, 'Príncipe', '/resources/images/cartas/principe.png')],
            [new Card(17, 6, 'Canciller', '/resources/images/cartas/canciller.png')],
            [new Card(18, 6, 'Canciller', '/resources/images/cartas/canciller.png')],
            [new Card(19, 7, 'Rey', '/resources/images/cartas/rey.png')],
            [new Card(20, 8, 'Doncella', '/resources/images/cartas/doncella.png')],
            [new Card(21, 9, 'Princesa', '/resources/images/cartas/princesa.png')],
        ];
    }
}
