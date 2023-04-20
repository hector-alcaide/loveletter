<?php

use App\Http\Controllers\API\PostsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('login', [\App\Http\Controllers\API\users::class, 'login']);
Route::post('register', [\App\Http\Controllers\API\users::class, 'register']);
Route::post('logout', [\App\Http\Controllers\API\users::class, 'logout'])->middleware(['auth:sanctum']);
Broadcast::routes(['middleware' => ['auth:sanctum']]);
Route::post('amigo', [\App\Http\Controllers\API\users::class, 'amigo']);
Route::post('addAmigo', [\App\Http\Controllers\API\amistades::class, 'addAmigo']);
Route::post('solicitudAmistad', [\App\Http\Controllers\API\amistades::class, 'solicitudAmistad']);
Route::post('aceptarSolicitudAmistad', [\App\Http\Controllers\API\amistades::class, 'aceptarSolicitudAmistad']);
Route::post('rechazarSolicitudAmistad', [\App\Http\Controllers\API\amistades::class, 'rechazarSolicitudAmistad']);
Route::post('tusAmigos', [\App\Http\Controllers\API\amistades::class, 'tusAmigos']);

Route::post('senddata', [PostsController::class,'senddata']);


Route::group(['prefix' => 'posts','middleware' => 'auth:sanctum'], function() {
    Route::get('/', [PostsController::class,'index']);
});

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
