<?php

use App\Http\Controllers\ToroVacaGameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('game')->group(function () {

Route::get('/tiempodejuego/{time}',[ToroVacaGameController::class,'timergame']);
Route::get('/ruta1',[ToroVacaGameController::class,'index1']);
Route::get('/ruta2',[ToroVacaGameController::class,'index2']);
Route::get('/ruta3',[ToroVacaGameController::class,'index3']);

});
