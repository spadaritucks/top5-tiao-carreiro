<?php

namespace App\Http\Controllers;

use App\Models\Recomendations;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecomendationsController extends Controller
{

    public function getAllRecomendations(){
        try{

            $recomendations = Recomendations::all();

            return response()->json(["recomendations" => $recomendations]);

        }catch(Exception $e){
            return response()->json(["message" => "Falha ao listar recomendações " . $e->getMessage()]);
        }
    }

    public function createRecomendation(Request $request)
    {

        try {

            DB::beginTransaction();

        
            $recomendation = Recomendations::create([
                "user_id" => $request->user_id,
                "youtube_url" => $request->youtube_url,
                "status" => $request->status
            ]);

            DB::commit();

            return response()->json([
                "message" => "Recomendação Enviada",
                "recomendation" => $recomendation
            ],201);


        } catch (Exception $e) {
            return response()->json(["message" => "Falha ao criar recomendação " . $e->getMessage()],500);
        }
    }
}
