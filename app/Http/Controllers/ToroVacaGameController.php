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

    public function timergame($timer){

       return [
        'msg' => 'Game Over. Tiempo agotado'
       ];
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
            ['nombre'=>$request->nombre, 'edad'=>$request->edad]
        );

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
        //
    }
}
