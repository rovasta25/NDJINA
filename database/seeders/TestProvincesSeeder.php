<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class TestProvincesSeeder extends Seeder
{
    public function run(): void
    {
        $comptes = [
            ['name' => 'Hôpital Régional de Franceville', 'email' => 'hopital.franceville@test.gab', 'type' => 'structure', 'structure_type' => 'hopital', 'phone' => '064111223', 'city' => 'Franceville', 'lat' => -1.6333, 'lng' => 13.5836],
            ['name' => 'Marcel MOUELE', 'email' => 'marcel.portgentil@test.gab', 'type' => 'particulier', 'structure_type' => null, 'phone' => '074224335', 'city' => 'Port-Gentil', 'lat' => -0.7193, 'lng' => 8.7815],
            ['name' => 'Clinique La Providence d\'Oyem', 'email' => 'clinique.oyem@test.gab', 'type' => 'structure', 'structure_type' => 'clinique', 'phone' => '065335446', 'city' => 'Oyem', 'lat' => 1.5995, 'lng' => 11.5797],
            ['name' => 'Laboratoire Albert Schweitzer', 'email' => 'labo.lambarene@test.gab', 'type' => 'structure', 'structure_type' => 'laboratoire', 'phone' => '064446557', 'city' => 'Lambaréné', 'lat' => -0.7001, 'lng' => 10.2406],
            ['name' => 'Josiane NKOUMOU', 'email' => 'josiane.mouila@test.gab', 'type' => 'particulier', 'structure_type' => null, 'phone' => '074557668', 'city' => 'Mouila', 'lat' => -1.8687, 'lng' => 11.0567],
            ['name' => 'Franck OBAME', 'email' => 'franck.koulamoutou@test.gab', 'type' => 'particulier', 'structure_type' => null, 'phone' => '074668779', 'city' => 'Koulamoutou', 'lat' => -1.1358, 'lng' => 12.4647],
            ['name' => 'Banque de Sang de Makokou', 'email' => 'banque.makokou@test.gab', 'type' => 'structure', 'structure_type' => 'banque_sang', 'phone' => '064779880', 'city' => 'Makokou', 'lat' => 0.5736, 'lng' => 12.8626],
            ['name' => 'Mireille BOUKANDOU', 'email' => 'mireille.tchibanga@test.gab', 'type' => 'particulier', 'structure_type' => null, 'phone' => '074880991', 'city' => 'Tchibanga', 'lat' => -2.9333, 'lng' => 11.0167],
        ];

        foreach ($comptes as $c) {
            User::updateOrCreate(
                ['email' => $c['email']],
                [
                    'name' => $c['name'],
                    'password' => 'Test2026Rova',
                    'type' => $c['type'],
                    'structure_type' => $c['structure_type'],
                    'phone' => $c['phone'],
                    'city' => $c['city'],
                    'latitude' => $c['lat'],
                    'longitude' => $c['lng'],
                ]
            );
        }
    }
}
