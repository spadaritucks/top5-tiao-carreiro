<?php

namespace App\Http\Controllers;

use App\Models\Approvals;
use App\Models\Music;
use App\Models\Recomendations;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage as Storage;

class MusicController extends Controller
{

    private function uploadImage($image)
    {
        try {
            $file = $image;
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'uploads/' . $fileName;
            Storage::disk('public')->putFileAs('uploads', $file, $fileName);
            return $filePath;
        } catch (Exception $e) {
            return response()->json(["message" => "Imagem não encontrada"]);
        }
    }


    public function getAllSongs () {
        try {

            $songs = DB::table('music')->orderBy("visualizations","desc")
            ->paginate(5);

            
            return response()->json(["songs" => $songs ],200);
            
        } catch (Exception $e) {
            return response()->json(["message" => "Falha ao listar as musicas " . $e->getMessage()], 400);
        }
    }

    public function approveOrCreateMusic(Request $request)
    {
        try {
            // Validar que approval seja um booleano
            $approval = filter_var($request->approval, FILTER_VALIDATE_BOOLEAN);
            
            DB::beginTransaction();

            Approvals::create([
                "recomendation_id" => $request->recomendation_id,
                "approval" => $approval
            ]);

            $recomendation = Recomendations::findOrFail($request->recomendation_id);
            $recomendation->update([
                "status" => $request->status,
            ]);

            if (!$approval) {
                DB::commit();
                return response()->json(["message" => "Musica Reprovada com Sucesso"]);
            }

            $thumb = $this->uploadImage($request->thumb);

            $music = Music::create([
                "recomendation_id" => $request->recomendation_id,
                "title" => $request->title,
                "thumb" => $thumb,
                "visualizations" => $request->visualizations
            ]);

            DB::commit();
            return response()->json(
                [
                    "message" => "Musica Aprovada e Criada com Sucesso",
                    "music" => $music
                ],201
            );
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "message" => "Erro ao registrar a musica " . $e->getMessage()
            ], 500);
        }
    }
}
