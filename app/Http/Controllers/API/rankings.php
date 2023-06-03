<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Amistad;
use App\Models\User;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class rankings extends Controller
{
    public function topWinners(){

        // $result = Game::
        //     select('users.alias', 'count(games.idWinner)')
        //     ->join('users', 'games.idWinner', '=', 'users.idUser')
        //     ->where('games.started',2)
        //     ->groupByRaw('games.idWinner') 
        //     ->order_by('games.idWinner', 'desc')
        //     ->get();

        $sql = 'SELECT users.alias, count(games.idWinner) AS games FROM games 
                JOIN users ON (users.idUser = games.idWinner)
                WHERE games.started = 2 GROUP BY users.alias ORDER by count(games.idWinner) desc limit 50';
        $result = DB::select($sql);

        return $result;

    }
}