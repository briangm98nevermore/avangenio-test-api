<?php

use App\Http\Controllers\ToroVacaGameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('game')->group(function () {

    Route::post('/CrearNuevoJuego',[ToroVacaGameController::class,'CrearNuevoJuego']);

    Route::middleware('VerificarTokenJWT')->group(function () {
        Route::delete('/EliminarJuego/{id}',[ToroVacaGameController::class,'destroy']);
        Route::get('/proponerCombinacion/{numero}',[ToroVacaGameController::class,'proponerCombinacion']);
        Route::get('/RespuestaPrevia/{numero}/{try}',[ToroVacaGameController::class,'RespuestaPrevia']);
    });

    Route::get('/store/{numero1}',[ToroVacaGameController::class,'store']);

});


