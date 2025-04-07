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



    private function extractYoutubeUrl($youtube_url)
    {
        $videoId = null;

        // Padrões de URL do YouTube
        $patterns = [
            '/youtube\.com\/watch\?v=([^&]+)/', // youtube.com/watch?v=ID
            '/youtu\.be\/([^?]+)/',            // youtu.be/ID
            '/youtube\.com\/embed\/([^?]+)/',   // youtube.com/embed/ID
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $youtube_url, $matches)) {
                $videoId = $matches[1];
                break;
            }
        }

        return $videoId;
    }

    private function getYoutubeVideoInfo($videoId)
    {

        $url = "https://www.youtube.com/watch?v=" . $videoId;

        // Inicializa o cURL
        $ch = curl_init();

        // Configura o cURL para a requisição
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
        ]);

        // Faz a requisição
        $response = curl_exec($ch);

        if ($response === false) {
            throw new Exception("Erro ao acessar o YouTube: " . curl_error($ch));
        }

        curl_close($ch);

        // Extrai o título
        if (!preg_match('/<title>(.+?) - YouTube<\/title>/', $response, $titleMatches)) {
            throw new Exception("Não foi possível encontrar o título do vídeo");
        }
        $title = html_entity_decode($titleMatches[1], ENT_QUOTES);

        // Extrai as visualizações
        // Procura pelo padrão de visualizações no JSON dos dados do vídeo
        if (preg_match('/"viewCount":\s*"(\d+)"/', $response, $viewMatches)) {
            $views = (int)$viewMatches[1];
        } else {
            // Tenta um padrão alternativo
            if (preg_match('/\"viewCount\"\s*:\s*{.*?\"simpleText\"\s*:\s*\"([\d,\.]+)\"/', $response, $viewMatches)) {
                $views = (int)str_replace(['.', ','], '', $viewMatches[1]);
            } else {
                $views = 0;
            }
        }

        if ($title === '') {
            throw new Exception("Vídeo não encontrado ou indisponível");
        }

        return [
            'title' => $title,
            'visualizations' => $views,
            'youtube_id' => $videoId,
            'thumb' => 'https://img.youtube.com/vi/' . $videoId . '/hqdefault.jpg'
        ];
    }


    public function getAllSongs()
    {
        try {

            $songs = Music::orderBy("visualizations", "desc")
                ->whereHas("recomendations.approvals", function($query) {
                    $query->where("approval", true);
                })
                ->paginate(5);


            return response()->json(["songs" => $songs], 200);
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

            $videoId = $this->extractYoutubeUrl($recomendation->youtube_url);
            $videoInfo = $this->getYoutubeVideoInfo($videoId);





            $music = Music::create([
                "recomendation_id" => $request->recomendation_id,
                "title" => $videoInfo['title'],
                "thumb" => $videoInfo["thumb"],
                "visualizations" => $videoInfo['visualizations']
            ]);

            DB::commit();
            return response()->json(
                [
                    "message" => "Configuração feita com Sucesso",
                    "music" => $music
                ],
                201
            );
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "message" => "Erro ao registrar a musica " . $e->getMessage()
            ], 500);
        }
    }
}
