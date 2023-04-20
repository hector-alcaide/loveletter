<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $table = 'partidas';

    protected $primaryKey = 'idPartida';

    protected $fillable = [
        'idJugador1',
        'idJugador2',
        'idJugador3',
        'idJugador4',
        'idJugador5',
        'idJugador6',
        'tipo',
        'empezada',
        'idGanador',
        'numeroVictoriasMaximas',
    ];

    protected $nullable = [
        'idJugador1',
        'idJugador2',
        'idJugador3',
        'idJugador4',
        'idJugador5',
        'idJugador6',
        'idGanador'
    ];
}
