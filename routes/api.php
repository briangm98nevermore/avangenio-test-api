<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ToroVacaGameController;
use App\Models\ToroVacaGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/logout',[AuthController::class,'logout']);

    Route::prefix('game')->group(function () {

        Route::post('/CrearNuevoJuego',[ToroVacaGameController::class,'CrearNuevoJuego']);
        Route::get('/store',[ToroVacaGameController::class,'store']);

        Route::middleware('VerificarTokenJWT')->group(function () {
            Route::delete('/EliminarJuego/{id}',[ToroVacaGameController::class,'destroy']);
            Route::get('/proponerCombinacion/{numero}',[ToroVacaGameController::class,'proponerCombinacion']);
            Route::get('/RespuestaPrevia/{numero}/{try}',[ToroVacaGameController::class,'RespuestaPrevia']);
        });

    });
});





