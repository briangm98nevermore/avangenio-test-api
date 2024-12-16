<?php

namespace App\Http\Middleware;

use App\Models\ToroVacaGame;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class VerificarTokenJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY'); // La clave API se envía en el encabezado

        $consulta = ToroVacaGame::latest('id')->first();

        // Buscar el cliente con esta clave API
        // $client = ToroVacaGame::where('api_key', $apiKey)->first();
       $client = Hash::check($apiKey, $consulta->api_key);

    //    $data = [
    //     'Token que lo genero'=>$apiKey,
    //     'Hash con el que comparar'=>$consulta->api_key,
    //     'client'=>$client
    //    ];

        if (!$client) {
            return response()->json(['error' => 'API Key inválida'], 401);
            //return response()->json($data);
        }

        // Si la clave API es válida, permite el acceso a la solicitud
        return $next($request);
    }

}
