<?php

namespace App\Http\Controllers;

use App\Models\ToroVacaGame;
use Hamcrest\Type\IsNumeric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ToroVacaGameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function gameOver()
    {
        return [
            'msg' => 'Game Over: Tiempo de juego terminado.'
        ];
    }

    public function timergame(){
       return 'timegame';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //CrearNuevoJuego
    }

    public function CrearNuevoJuego(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'nombre'=>'required|max:255',
            'edad'=>'required|digits_between:1,2'
        ]);

        if ($validator->fails()) {
            $data = [
                'msg'=>'Error en la validacion de los datos',
                'errores'=>$validator->errors(),
                'status' => 400
            ];

            return response()->json($data,400);
        }

        $id = DB::table('toro_vaca_games')->insertGetId(
            ['nombre'=>$request->nombre,
             'edad'=>$request->edad,
             'numeroPropuesto'=>fake()->randomNumber(4,true),
             ]
        );

        if (!$id) {
            $data = [
                'msg'=>'Error en la creacion del juego',
                'status' => 500
            ];

            return response()->json($data,500);
        }

       return response()->json($id,200);

    }

    /**
     * Display the specified resource.
     */
    public function proponerCombinacion(string $id)
    {

        if (!is_numeric($id)) {
            $data=[
                'msg'=>'El valor no es numerico',
                'valor'=>$id,
                'status'=>500
            ];
            return response()->json($data,500);
        }

        $consulta = ToroVacaGame::latest('id')->first();
       // return response()->json($idData,200);

        $datagame = [
            'numero propuesto'=>$id,
            'Cantidad de toros y vacas alcanzados'
        ];

        return response()->json($consulta,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $game = ToroVacaGame::find($id);

        if (!$game) {
            $data = [
                'msg'=>'El juego no existe.',
                'status' => 500
            ];

            return response()->json($data,500);
        }

        $game->delete();

        $data = [
            'msg'=>'Juego eliminado correctamente',
            'status' => 200
        ];

        return response()->json($data,200);
    }
}
