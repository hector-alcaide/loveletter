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
        'idAnfitrion',
        'tipo',
        'empezada',
        'idGanador',
        'numeroVictoriasMaximas',
        'partida'
    ];

    protected $nullable = [
        'partida',
        'idGanador'
    ];

    public function jugadores()
    {
        return $this->belongsToMany(User::class, 'partidasusuarios', 'idPartida', 'idJugador');
    }

}
