<?php

namespace App\Http\Controllers;

use App\Models\ToroVacaGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ToroVacaGameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function gameOver()
    {
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
             'numeroIntentos'=>1
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

        if (!is_numeric($id)) {     //comprobar que el dato sea numerico
            $data=[
                'msg'=>'El valor no es numerico',
                'valor'=>$id,
                'status'=>500
            ];
            return response()->json($data,500);
        }
        if (strlen($id)!=4) {   //comprobar que el numero sea de 4 digitos
            $data=[
                'msg'=>'El valor debe ser de cuatro digitos',
                'valor'=>$id,
                'status'=>500
            ];
            return response()->json($data,500);
        }


        $consulta = ToroVacaGame::latest('id')->first();    //obtener ultimo registro de la tabla
        $string=(string)$consulta->numeroPropuesto;     //parsea un numerico a string
        $idNumeroPropuesto=str_split($string);      //convierte string to array
        $consulta->increment('numeroIntentos');     //incrementar contador de intentos en 1

        $idNumeroIntentos=$consulta->numeroIntentos;
        $numeroToros=0;
        $numeroVacas=0;

        for ($i=0; $i < strlen($id); $i++) {
            if ($id[$i]==$idNumeroPropuesto[$i]) {
                $numeroToros++;
            }else if (in_array($id[$i],$idNumeroPropuesto)==true) {
                $numeroVacas++;
            }
        }

        $datagame = [
            'numero propuesto'=>$id,
            'Cantidad de toros alcanzados'=>$numeroToros,
            'Cantidad de vacas alcanzados'=>$numeroVacas,
            'Numero de intentos alcanzados'=>$idNumeroIntentos,
        ];

        return response()->json($datagame,200);
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
