<?php

namespace App\Http\Controllers;

use App\Models\ToroVacaGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Info(
 *    title="AVANGENIO-TEST-API-DOCUMENTATION",
 *    version="3.0.0",
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     securityScheme="bearerAuth",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )

 */

class ToroVacaGameController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(int $numero1, int $numero2)
    {
        $data = [
            'numero1'=>$numero1,
            'numero2'=>$numero2,
        ];

        return $data;
    }

    /**
     * @OA\Post(
     *     path="/api/game/CrearNuevoJuego",
     *     summary="Endpoint para crear un nuevo juego",
     *     @OA\Parameter(
     *         name="nombre",
     *         in="query",
     *         description="Nombre del usuario",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="edad",
     *         in="query",
     *         description="Edad del usuario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Juego creado correctamente"),
     *     @OA\Response(response="500", description="Error en la creacion del juego"),
     *     @OA\Response(response="400", description="Error en la validacion de los datos")
     * )
     */
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

        ToroVacaGame::create(
            ['nombre'=>$request->nombre,
             'edad'=>$request->edad,
             'numeroPropuesto'=>fake()->randomNumber(4,true),
             'numeroIntentos'=>1
             ]
        );

        $id = ToroVacaGame::latest('id')->first();

        if (!$id) {
            $data = [
                'msg'=>'Error en la creacion del juego',
                'status' => 500
            ];
            return response()->json($data,500);
        }

        $data = [
            'msg'=>'Juego creado correctamente',
            'Identificador'=>$id->id,
            'status' => 200
        ];

       return response()->json($data,200);
    }

    /**
     * @OA\Get(
     *     path="/api/game/proponerCombinacion/{numero}",
     *     summary="Endpoint para validar el numero propuesto",
     *     @OA\Parameter(
     *         name="numero",
     *         in="path",
     *         description="Nombre del usuario",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(response="501", description="El valor no es numerico"),
     *     @OA\Response(response="502", description="El valor debe ser de cuatro digitos"),
     *     @OA\Response(response="201", description="Este juego ya ha sido jugado con anterioridad"),
     *     @OA\Response(response="202", description="Game Over. Tiempo expirado"),
     *     @OA\Response(response="203", description="Game Win. Numero adivinado")
     * )
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

        $DateAndTime1 = localtime(time(), true);
        $tiempoNow = ($DateAndTime1['tm_min']*60)+$DateAndTime1['tm_sec'];//segundos actual

         $consulta = ToroVacaGame::latest('id')->first();
         $parser = (string)$consulta->created_at;
         $salida = str_split($parser);
         $timeServer = array_slice($salida,14);
         $minutos = $timeServer[0] . $timeServer[1];
         $segundos = $timeServer[3] . $timeServer[4];

         $minutos = intval($minutos);
         $segundos = intval($segundos);

          $arrayTimeDB = [
             'tiempo en minutos'=>$minutos,
             'tiempo en segundos'=>$segundos
         ];

        $tiempoRealDB = ($arrayTimeDB['tiempo en minutos']*60)+$arrayTimeDB['tiempo en segundos'];//tiempo DB

        $consulta = ToroVacaGame::latest('id')->first();    //obtener ultimo registro de la tabla
        $string=(string)$consulta->numeroPropuesto;     //parsea un numerico a string
        $idNumeroPropuesto=str_split($string);      //convierte string to array

        $idNumeroIntentos=$consulta->numeroIntentos;
        $numeroToros=0;
        $numeroVacas=0;
        $tiempoDisponible =300-($tiempoNow-$tiempoRealDB);
      //  $timeLocal = $tiempoNow-$tiempoRealDB;
        $evaluacion = ($tiempoDisponible/2)+$idNumeroIntentos;

        //************************* */

        if (!is_null($consulta->estado)) {  //VALIDAR SI EL JUEGO YA FUE JUGADO
            $data = [
                'msg'=>'Este juego ya ha sido jugado con anterioridad',
                'status'=>200
            ];
            return response()->json($data,200);
        }

        if ((($tiempoNow-$tiempoRealDB)>env('TIME_GAME'))) { //VALIDAR SI QUEDA TIEMPO

            $dataganados = DB::table('toro_vaca_games')->where('estado', '=',1)->orderByDesc('evaluacion')->get('evaluacion');
            $dataperdidos = DB::table('toro_vaca_games')->where('estado', '=',0)->orderByDesc('evaluacion')->get('evaluacion');
            $data1 = json_decode($dataganados,true);
            $cont = count($data1);
            $data2 = json_decode($dataperdidos,true);
            $cont2=0;
            foreach ($data2 as $key => $value) {
                foreach ($value as $key => $value) {
                 if ($value>$evaluacion) {
                     $cont2++;
                 }
                }
             }

             $data = [
                'msg'=>'Game Over. Tiempo terminado',
                'Numero secreto'=>$string,
                'Ranking'=>($cont2+1)+$cont,
                'status'=>200
            ];

            $consulta->estado = false;
            $consulta->evaluacion = $evaluacion;
            $consulta->ranking = ($cont2+1)+$cont;
            $consulta->save();

            return response()->json($data);
        }
        //************************* */

        if ($id==$string) { // VALIDACION DE JUEGO GANADO

            $dataganados = DB::table('toro_vaca_games')->where('estado', '=',1)->orderByDesc('evaluacion')->get('evaluacion');
            $data1 = json_decode($dataganados,true);
            $cont1=0;
            foreach ($data1 as $key => $value) {
                foreach ($value as $key => $value) {
                 if ($value>$evaluacion) {
                     $cont1++;
                 }
                }
             }

             $data = [
                'msg'=>'Game Win. Numero adivinado',
                'Numero secreto'=>$string,
                'Ranking'=>$cont1+1,
                'status'=>200
            ];

            $consulta->estado = true;
            $consulta->evaluacion = $evaluacion;
            $consulta->ranking = $cont1+1;
            $consulta->save();

            return response()->json($data,200);
        }

        //************************* */

        for ($i=0; $i < strlen($id); $i++)
        {    //CALCULAR CANTIDAD DE TOROS Y VACAS
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
            'Tiempo disponible en segundos'=>$tiempoDisponible,
            'Evaluacion'=>$evaluacion
        ];

        $consulta->increment('numeroIntentos');     //incrementar contador de intentos en 1

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

     /**
     * @OA\Delete(
     *     path="/api/game/EliminarJuego/{id}",
     *     summary="Endpoint para eliminar un juego existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id del juego a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response="200", description="Juego eliminado correctamente"),
     *     @OA\Response(response="500", description="El juego no existe.")
     * )
     */
    public function destroy(int $id)
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

    public function RespuestaPrevia(string $id,int $numero)
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

        $DateAndTime1 = localtime(time(), true);
        $tiempoNow = ($DateAndTime1['tm_min']*60)+$DateAndTime1['tm_sec'];//segundos actual

         $consulta = ToroVacaGame::latest('id')->first();
         $parser = (string)$consulta->created_at;
         $salida = str_split($parser);
         $timeServer = array_slice($salida,14);
         $minutos = $timeServer[0] . $timeServer[1];
         $segundos = $timeServer[3] . $timeServer[4];

         $minutos = intval($minutos);
         $segundos = intval($segundos);

          $arrayTimeDB = [
             'tiempo en minutos'=>$minutos,
             'tiempo en segundos'=>$segundos
         ];

        $tiempoRealDB = ($arrayTimeDB['tiempo en minutos']*60)+$arrayTimeDB['tiempo en segundos'];//tiempo DB

        $consulta = ToroVacaGame::latest('id')->first();    //obtener ultimo registro de la tabla
        $string=(string)$consulta->numeroPropuesto;     //parsea un numerico a string
        $idNumeroPropuesto=str_split($string);      //convierte string to array

        $idNumeroIntentos=$numero;
        $numeroToros=0;
        $numeroVacas=0;
        $tiempoDisponible =300-($tiempoNow-$tiempoRealDB);
        $evaluacion = ($tiempoDisponible/2)+$idNumeroIntentos;

        //************************* */

        if (!is_null($consulta->estado)) {  //VALIDAR SI EL JUEGO YA FUE JUGADO
            $data = [
                'msg'=>'Este juego ya ha sido jugado con anterioridad',
                'status'=>200
            ];
            return response()->json($data,200);
        }

        // if ((($tiempoNow-$tiempoRealDB)>env('TIME_GAME'))) { //VALIDAR SI QUEDA TIEMPO

        //     $dataganados = DB::table('toro_vaca_games')->where('estado', '=',1)->orderByDesc('evaluacion')->get('evaluacion');
        //     $dataperdidos = DB::table('toro_vaca_games')->where('estado', '=',0)->orderByDesc('evaluacion')->get('evaluacion');
        //     $data1 = json_decode($dataganados,true);
        //     $cont = count($data1);
        //     $data2 = json_decode($dataperdidos,true);
        //     $cont2=0;
        //     foreach ($data2 as $key => $value) {
        //         foreach ($value as $key => $value) {
        //          if ($value>$evaluacion) {
        //              $cont2++;
        //          }
        //         }
        //      }

        //      $data = [
        //         'msg'=>'Game Over. Tiempo terminado',
        //         'Numero secreto'=>$string,
        //         'Ranking'=>($cont2+1)+$cont,
        //         'status'=>200
        //     ];

        //     $consulta->estado = false;
        //     $consulta->evaluacion = $evaluacion;
        //     $consulta->ranking = ($cont2+1)+$cont;
        //     $consulta->save();

        //     return response()->json($data);
        // }
        // //************************* */

        // if ($id==$string) { // VALIDACION DE JUEGO GANADO

        //     $dataganados = DB::table('toro_vaca_games')->where('estado', '=',1)->orderByDesc('evaluacion')->get('evaluacion');
        //     $data1 = json_decode($dataganados,true);
        //     $cont1=0;
        //     foreach ($data1 as $key => $value) {
        //         foreach ($value as $key => $value) {
        //          if ($value>$evaluacion) {
        //              $cont1++;
        //          }
        //         }
        //      }

        //      $data = [
        //         'msg'=>'Game Win. Numero adivinado',
        //         'Numero secreto'=>$string,
        //         'Ranking'=>$cont1+1,
        //         'status'=>200
        //     ];

        //     $consulta->estado = true;
        //     $consulta->evaluacion = $evaluacion;
        //     $consulta->ranking = $cont1+1;
        //     $consulta->save();

        //     return response()->json($data,200);
        // }

        //************************* */

        for ($i=0; $i < strlen($id); $i++)
        {    //CALCULAR CANTIDAD DE TOROS Y VACAS
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
            'Tiempo disponible en segundos'=>$tiempoDisponible,
            'Evaluacion'=>$evaluacion
        ];

       // $consulta->increment('numeroIntentos');     //incrementar contador de intentos en 1

        return response()->json($datagame,200);
    }
}
