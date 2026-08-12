<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'email' => 'admin@ndjina.gab',
                'name' => 'Administrateur NDJINA',
                'password' => 'AdminNdjina2026!',
            ],
            [
                'email' => 'admin1@ndjina.gab',
                'name' => 'Administrateur NDJINA 1',
                'password' => 'AdminNdjina1!',
            ],
            [
                'email' => 'admin2@ndjina.gab',
                'name' => 'Administrateur NDJINA 2',
                'password' => 'AdminNdjina2!',
            ],
        ];

        foreach ($admins as $admin) {
            User::firstOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => $admin['password'],
                    'type' => 'admin',
                    'phone' => null,
                    'city' => 'Libreville',
                ]
            );
        }
    }
}
