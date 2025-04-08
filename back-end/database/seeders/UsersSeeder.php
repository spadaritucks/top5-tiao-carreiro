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
        DB::beginTransaction();
        
        $user = Users::create([
            'name' => "Thiago Oliveira",
            'email' => "thiago@mail.com",
            'password' => Hash::make("admin"),
        ]);
        
        // Cria o tipo do usuário
        Types::create([
            'user_id' => $user->id,
            'type' => "admin",
        ]);

        DB::commit();

        DB::beginTransaction();
        
        $user = Users::create([
            'name' => "Mario Gomes",
            'email' => "mario@mail.com",
            'password' => Hash::make("client"),
        ]);
        
        // Cria o tipo do usuário
        Types::create([
            'user_id' => $user->id,
            'type' => "client",
        ]);

        DB::commit();
    }
} 