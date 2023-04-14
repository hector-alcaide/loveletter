<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Deck;
use Illuminate\Http\Request;

class games extends Controller
{
    public function newGame(){

        $deck = new Deck();
        $card = new Card(1, 0, 'Espía', '/resources/images/cartas/espia.png');

        return json_encode($card);
    }
}
