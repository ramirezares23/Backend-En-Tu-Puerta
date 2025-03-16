<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Petition;
use App\Models\Service;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users_client = User::factory(5)->create();

        $users_workers = User::factory(5)->create();
        
        $services = Service::factory(5)->recycle($users_workers)->create();
        
        $petitions = Petition::factory(15)->recycle($users_client, $services)->create();

        // Hay que hacer 1 usuario cliente y 2 usuarios prestador.
        // Minimo 3 servicios predefinidos para cada usuario prestador
        //
        // Esto es un ejemplo de cuando vimos el curso:
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
