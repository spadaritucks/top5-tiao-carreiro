<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller

{

    public function getAllUsers()
    {
        try {
            $users = Users::all();
            return response()->json(["users" => $users]);
        } catch (Exception $e) {
            return response()->json(["message" => "Falha ao listar os usuarios " . $e->getMessage()], 400);
        }
    }

    public function getUserById(String $id)
    {
        try {
            $user = Users::findOrFail($id);
            return response()->json(["user" => $user]);
        } catch (Exception $e) {
            return response()->json(["message" => "Falha ao listar o usuario " . $e->getMessage()], 400);
        }
    }


    public function createUser(Request $request)
    {


        try {
            DB::beginTransaction();
            $user = Users::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password)
            ]);

            DB::commit();

            return response()->json([
                "message" => "Usuario Criado com Sucesso",
                "user" => $user
            ], 201);
        } catch (Exception $e) {
            return response()->json(["message" => "Falha ao criar o usuario " . $e->getMessage()], 400);
        }
    }
}
