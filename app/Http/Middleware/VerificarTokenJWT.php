<?php

namespace App\Http\Middleware;

use App\Models\ToroVacaGame;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

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

        // Buscar el cliente con esta clave API
        $client = ToroVacaGame::where('api_key', $apiKey)->first();

        if (!$client) {
            return response()->json(['error' => 'API Key inválida'], 401);
        }

        // Si la clave API es válida, permite el acceso a la solicitud
        return $next($request);
    }

}
