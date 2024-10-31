<?php

use App\Http\Controllers\ToroVacaGameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('game')->group(function () {

Route::get('/tiempodejuego',[ToroVacaGameController::class,'timergame']);
Route::post('/CrearNuevoJuego',[ToroVacaGameController::class,'CrearNuevoJuego']);
Route::delete('/EliminarJuego/{id}',[ToroVacaGameController::class,'destroy']);
Route::get('/proponerCombinacion/{numero}',[ToroVacaGameController::class,'proponerCombinacion']);

});
