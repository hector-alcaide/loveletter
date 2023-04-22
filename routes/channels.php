<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('play.game.{idPartida}', function ($user) {
    $user->conexion_canal = now()->toIso8601String();
    return $user;
});

Broadcast::channel('games.list', function () {
    return true;
});

Broadcast::channel('join.game.{idPartida}', function ($user) {
    $user->conexion_canal = now()->toIso8601String();
    return $user;
});

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
