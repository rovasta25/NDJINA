<?php

namespace Database\Seeders;

use App\Models\GroupeSanguin;
use Illuminate\Database\Seeder;

class GroupeSanguinSeeder extends Seeder
{
    public function run(): void
    {
        $groupes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        foreach ($groupes as $libelle) {
            GroupeSanguin::firstOrCreate(['libelle' => $libelle]);
        }
    }
}
