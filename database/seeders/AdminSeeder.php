<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@ndjina.gab'],
            [
                'name' => 'Administrateur NDJINA',
                'password' => 'AdminNdjina2026!',
                'type' => 'admin',
                'phone' => null,
                'city' => 'Libreville',
            ]
        );
    }
}
