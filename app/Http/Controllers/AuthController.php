<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){

        $validator = Validator::make($request->all(),[
            'nombre' => 'required|string|max:255',
            'edad' => 'required|integer|between:5,99',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->nombre,
            'edad' => $request->edad,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['data'=>$user, 'acces_token'=>$token, 'token_type'=> 'Bearer']);
    }


    public function login(Request $request){

        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json(['msg' => 'Sin acceso'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'msg' => 'Hola '.$user->name,
            'accesToken' => $token,
            'tokenType' => 'Bearer',
            'user' => $user
        ]);
    }

    public function logout(){

        if (!Auth::check()) {
            return response()->json(['msg' => 'No hay usuario autenticado'], 401);
        }

        auth()->user()->tokens()->delete();

        return response()->json(['msg' => 'Usuario desautenticado correctamente'], 200);
    }
}
