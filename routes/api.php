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

Route::post('getidusuario', [games::class, 'getIdUsuario']);

Route::post('getgamedata', [games::class, 'getGameData']);
Route::post('getgameslist', [games::class, 'getGamesActive']);
Route::post('newgame', [games::class, 'nuevaPartida']);
Route::post('preparegame', [games::class, 'prepararPartida']);
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
