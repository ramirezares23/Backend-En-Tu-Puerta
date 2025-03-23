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
        $this->call(FixedEntitiesSeeder::class);
        
        $users_client = User::factory(5)->create();

        $users_workers = User::factory(5)->create();
        
        $services = Service::factory(5)->recycle($users_workers)->create();
        
        $petitions = Petition::factory(15)->recycle($users_client, $services)->create();
    }
}
