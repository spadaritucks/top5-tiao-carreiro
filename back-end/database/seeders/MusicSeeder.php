<?php

namespace Database\Seeders;

use App\Models\Approvals;
use App\Models\Music;
use App\Models\Recomendations;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MusicSeeder extends Seeder
{
    public function run(): void
    {

        DB::beginTransaction();
        $initialData = [
            [
                'title' => 'O Mineiro e o Italiano',
                'visualizations' => 5200000,
                'youtube_id' => 's9kVG2ZaTS4',
            ],
            [
                'title' => 'Pagode em Brasília',
                'visualizations' => 5000000,
                'youtube_id' => 'lpGGNA6_920',
            ],
            [
                'title' => 'Rio de Lágrimas',
                'visualizations' => 153000,
                'youtube_id' => 'FxXXvPL3JIg',
            ],
            [
                'title' => 'Tristeza do Jeca',
                'visualizations' => 154000,
                'youtube_id' => 'tRQ2PWlCcZk',
            ],
            [
                'title' => 'Terra roxa',
                'visualizations' => 3300000,
                'youtube_id' => '4Nb89GFu2g4',
            ],
        ];

        foreach ($initialData as $data) {
            // 1. Cria a recomendação
            $recomendation = Recomendations::create([
                'user_id' => 1,
                'youtube_url' => "https://www.youtube.com/watch?v={$data['youtube_id']}",
                'status' => 'approved', // ajuste conforme sua lógica
            ]);

            // 2. Aprova a recomendação
            Approvals::create([
                'recomendation_id' => $recomendation->id,
                'approval' => 1,
            ]);

            // 3. Cria o registro da música
            Music::create([
                'recomendation_id' => $recomendation->id,
                'title' => $data['title'],
                'thumb' => "https://img.youtube.com/vi/{$data['youtube_id']}/hqdefault.jpg",
                'visualizations' => $data['visualizations'],
            ]);
        }
        DB::commit();
    }
}
