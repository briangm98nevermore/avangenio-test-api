<?php

namespace App\Http\Controllers;

use App\Models\ToroVacaGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ToroVacaGameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return [
            'msg' => 'Juego en proceso'
        ];
    }

    public function timergame(){
        static $staticVar = 0;
        $staticVar++;
        return $staticVar;
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
    public function show(string $id)
    {
        //
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
