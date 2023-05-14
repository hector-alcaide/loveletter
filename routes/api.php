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

//games
Route::post('getgamedata', [games::class, 'getGameData']);
Route::post('getgameslist', [games::class, 'getGamesActive']);
Route::post('newgame', [games::class, 'newGame']);
Route::post('preparegame', [games::class, 'prepareGame']);
Route::post('stealcard', [games::class, 'stealCard']);
Route::post('playcard', [games::class, 'resolvePlay']);
Route::post('resolvechancellor', [games::class, 'resolveChancellor']);
Route::post('updateround', [games::class, 'updateRound']);
Route::post('endgame', [games::class, 'endGame']);

//friends
Route::post('searchFriend', [\App\Http\Controllers\API\users::class, 'searchFriend']);
Route::post('searchNewFriend', [\App\Http\Controllers\API\friends::class, 'searchNewFriend']);
Route::post('addFriend', [\App\Http\Controllers\API\friends::class, 'addFriend']);
Route::post('requestFriend', [\App\Http\Controllers\API\friends::class, 'requestFriend']);
Route::post('acceptRequestInvitation', [\App\Http\Controllers\API\friends::class, 'acceptRequestInvitation']);
Route::post('rejectRequestInvitation', [\App\Http\Controllers\API\friends::class, 'rejectRequestInvitation']);
Route::post('yourFriends', [\App\Http\Controllers\API\friends::class, 'yourFriends']);

//profile
Route::post('yourProfile', [\App\Http\Controllers\API\profiles::class, 'yourProfile']);
Route::post('findAlias', [\App\Http\Controllers\API\profiles::class, 'findAlias']);
Route::post('updateProfile', [\App\Http\Controllers\API\profiles::class, 'updateProfile']);

//Ranking
Route::post('topwinners', [\App\Http\Controllers\API\rankings::class, 'topWinners']);
