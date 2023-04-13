<?php

use App\Http\Controllers\API\PostsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('login', [\App\Http\Controllers\API\users::class, 'login']);
Route::post('register', [\App\Http\Controllers\API\users::class, 'register']);
Route::post('logout', [\App\Http\Controllers\API\users::class, 'logout'])->middleware(['auth:sanctum']);
Broadcast::routes(['middleware' => ['auth:sanctum']]);
Route::post('senddata', [PostsController::class,'senddata']);

Route::group(['prefix' => 'posts','middleware' => 'auth:sanctum'], function() {
    Route::get('/', [PostsController::class,'index']);
});

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
