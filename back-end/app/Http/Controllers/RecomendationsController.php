<?php

namespace App\Http\Controllers;

use App\Models\Recomendations;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecomendationsController extends Controller
{

    public function createRecomendation(Request $request)
    {

        try {

            DB::beginTransaction();

            

            $recomendation = Recomendations::create([
                "user_id" => $request->user_id,
                "youtube_url" => $request->youtube_url
            ]);

            DB::commit();

            return response()->json([
                "message" => "Recomendação Enviada",
                "recomendation" => $recomendation
            ]);


        } catch (Exception $e) {
            return response()->json(["message" => "Falha ao criar recomendação " . $e->getMessage()]);
        }
    }
}
