<?php

use Illuminate\Support\Facades\Route;
use App\Events\Hello;
use App\Events\PrivateTest;
use App\Events\GameChannel;


Route::get('/broadcastPrivate',function(){
    $user = App\Models\User::find(5);
    broadcast(new PrivateTest($user));
    return "Event has been sent!";
});

Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');

