<?php

namespace App\Http\Controllers;

use App\Models\Types;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function authLogin(Request $request){
        if(Auth::attempt($request->only('email', 'password'))){
            $user = $request->user();
            $type = Types::where("user_id", $user->id)->first();

            return response()->json([
                "token" => $user->createToken('api_token')->plainTextToken,
                'user' => $user,
                "type" => $type,
                'message' => 'Login realizado com sucesso'
            ],200);
        }
        
        return response()->json([
            'message' => 'Credenciais inválidas'
        ], 401);
    }
}
