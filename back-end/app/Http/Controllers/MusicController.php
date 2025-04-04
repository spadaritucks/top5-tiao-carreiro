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

    public function approveOrCreateMusic(Request $request)
    {
        try {

            DB::beginTransaction();

            Approvals::create([
                "recomendation_id" => $request->recomendation_id,
                "approval" => $request->approval
            ]);

            $recomendation = Recomendations::findOrFail($request->recomendation_id);
            $recomendation->update([
                "status" => $request->status,
            ]);

            if ($request->approval == false) {
                return response()->json(["message" => "Musica Reprovada com Sucesso"]);
            }

            $thumb = $this->uploadImage($request->thumb);

            $music = Music::create([
                "recomendation_id" => $request->recomendation_id,
                "title" => $request->title,
                "thumb" => $thumb,
                "visualizations" => $request->visualizations
            ]);

            return response()->json(
                [
                    "message" => "Musica Aprovada e Criada com Sucesso",
                    "music" => $music
                ]

            );

            DB::commit();
        } catch (Exception $e) {
            return response()->json([
                "message" => "Erro ao registrar a musica " . $e->getMessage()
            ]);
        }
    }
}
