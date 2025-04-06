<?php

namespace Database\Seeders;

use App\Models\Types;
use App\Models\Users;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Carrega o arquivo JSON
        $jsonPath = database_path('users.json');
        $jsonData = json_decode(file_get_contents($jsonPath), true);
        
        // Inicia uma transação
        DB::beginTransaction();
        
        try {
            foreach ($jsonData['users'] as $userData) {
                // Cria o usuário
                $user = Users::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                ]);
                
                // Cria o tipo do usuário
                Types::create([
                    'user_id' => $user->id,
                    'type' => $userData['type'],
                ]);
            }
            
            // Confirma a transação
            DB::commit();
            
            $this->command->info('Usuários importados com sucesso!');
        } catch (\Exception $e) {
            // Reverte a transação em caso de erro
            DB::rollBack();
            
            $this->command->error('Erro ao importar usuários: ' . $e->getMessage());
        }
    }
} 