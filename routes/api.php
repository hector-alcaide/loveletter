<?php

use App\Http\Controllers\API\games;
use App\Http\Controllers\API\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [users::class, 'login']);
Route::post('register', [users::class, 'register']);
Route::post('logout', [users::class, 'logout'])->middleware(['auth:sanctum']);

Broadcast::routes(['middleware' => ['auth:sanctum']]);
Route::post('amigo', [\App\Http\Controllers\API\users::class, 'amigo']);
Route::post('addAmigo', [\App\Http\Controllers\API\amistades::class, 'addAmigo']);
Route::post('solicitudAmistad', [\App\Http\Controllers\API\amistades::class, 'solicitudAmistad']);
Route::post('aceptarSolicitudAmistad', [\App\Http\Controllers\API\amistades::class, 'aceptarSolicitudAmistad']);
Route::post('rechazarSolicitudAmistad', [\App\Http\Controllers\API\amistades::class, 'rechazarSolicitudAmistad']);
Route::post('tusAmigos', [\App\Http\Controllers\API\amistades::class, 'tusAmigos']);

Route::post('getgameslist', [games::class, 'getGamesActive']);
Route::post('newgame', [games::class, 'nuevaPartida']);

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
