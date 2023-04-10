<?php

use Illuminate\Support\Facades\Route;
use App\Events\Hello;
use App\Events\PrivateTest;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/broadcast',function(){

    broadcast(new Hello());
    return "Evento ejecutado!";
});

Route::get('/broadcastPrivate',function(){
    $user = App\Models\User::find(1);
    broadcast(new PrivateTest($user));
    return "Event has been sent!";
});

Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');
