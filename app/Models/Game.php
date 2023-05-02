<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $table = 'games';

    protected $primaryKey = 'idGame';

    protected $fillable = [
        'idHost',
        'type',
        'started',
        'idWinner',
        'numMaxWins',
        'game'
    ];

    protected $nullable = [
        'game',
        'idWinner'
    ];

    public function players()
    {
        return $this->belongsToMany(User::class, 'gamesusers', 'idGame', 'idPlayer');
    }

}
