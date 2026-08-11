<?php

namespace Database\Seeders;

use App\Models\Alerte;
use App\Models\GroupeSanguin;
use App\Models\User;
use Illuminate\Database\Seeder;

class AlertesSeeder extends Seeder
{
    public function run(): void
    {
        $comptesManquants = [
            ['name' => 'Rova MOUANDZA', 'email' => 'rova@test.gab', 'type' => 'particulier', 'phone' => '074000111', 'city' => 'Libreville'],
            ['name' => 'CHL Chimie', 'email' => 'chl@test.gab', 'type' => 'structure', 'structure_type' => 'laboratoire', 'phone' => '064000222', 'city' => 'Libreville', 'password' => 'Struct2026X'],
        ];

        foreach ($comptesManquants as $c) {
            User::updateOrCreate(
                ['email' => $c['email']],
                [
                    'name' => $c['name'],
                    'password' => $c['password'] ?? 'Test2026Rova',
                    'type' => $c['type'],
                    'structure_type' => $c['structure_type'] ?? null,
                    'phone' => $c['phone'],
                    'city' => $c['city'],
                ]
            );
        }

        $groupe = fn (string $libelle) => GroupeSanguin::where('libelle', $libelle)->value('id');

        $alertes = [
            ['user' => 'rova@test.gab', 'groupe' => 'B+', 'quantite' => 2, 'urgence' => 'haute', 'lieu' => 'CHU de Libreville', 'ville' => 'Libreville', 'statut' => 'active', 'date' => '2026-08-10 16:47:49'],
            ['user' => 'chl@test.gab', 'groupe' => 'O+', 'quantite' => 3, 'urgence' => 'haute', 'lieu' => 'Hôpital Melen', 'ville' => 'Libreville', 'statut' => 'active', 'date' => '2026-08-10 15:47:49'],
            ['user' => 'hopital.franceville@test.gab', 'groupe' => 'A+', 'quantite' => 1, 'urgence' => 'moyenne', 'lieu' => 'Hôpital Régional de Franceville', 'ville' => 'Franceville', 'statut' => 'active', 'date' => '2026-08-10 13:47:49'],
            ['user' => 'rova@test.gab', 'groupe' => 'O-', 'quantite' => 2, 'urgence' => 'basse', 'lieu' => 'Clinique Nzeng-Ayong', 'ville' => 'Libreville', 'statut' => 'active', 'date' => '2026-08-09 18:47:49'],
            ['user' => 'marcel.portgentil@test.gab', 'groupe' => 'AB+', 'quantite' => 4, 'urgence' => 'moyenne', 'lieu' => 'Hôpital de Port-Gentil', 'ville' => 'Port-Gentil', 'statut' => 'active', 'date' => '2026-08-08 18:47:49'],
            ['user' => 'clinique.oyem@test.gab', 'groupe' => 'B-', 'quantite' => 2, 'urgence' => 'haute', 'lieu' => 'Clinique La Providence', 'ville' => 'Oyem', 'statut' => 'active', 'date' => '2026-08-07 18:47:49'],
            ['user' => 'chl@test.gab', 'groupe' => 'A+', 'quantite' => 3, 'urgence' => 'moyenne', 'lieu' => 'Hôpital Omar Bongo Ondimba', 'ville' => 'Libreville', 'statut' => 'active', 'date' => '2026-08-10 14:48:48'],
            ['user' => 'hopital.franceville@test.gab', 'groupe' => 'O-', 'quantite' => 3, 'urgence' => 'haute', 'lieu' => 'Hôpital Régional de Franceville', 'ville' => 'Franceville', 'statut' => 'active', 'date' => '2026-08-10 12:48:48'],
            ['user' => 'marcel.portgentil@test.gab', 'groupe' => 'A-', 'quantite' => 2, 'urgence' => 'moyenne', 'lieu' => 'Hôpital de Port-Gentil', 'ville' => 'Port-Gentil', 'statut' => 'active', 'date' => '2026-08-10 10:48:48'],
            ['user' => 'clinique.oyem@test.gab', 'groupe' => 'O+', 'quantite' => 2, 'urgence' => 'basse', 'lieu' => 'Clinique La Providence', 'ville' => 'Oyem', 'statut' => 'active', 'date' => '2026-08-10 08:48:48'],
            ['user' => 'labo.lambarene@test.gab', 'groupe' => 'AB-', 'quantite' => 2, 'urgence' => 'moyenne', 'lieu' => 'Hôpital Albert Schweitzer', 'ville' => 'Lambaréné', 'statut' => 'active', 'date' => '2026-08-10 06:48:48'],
            ['user' => 'labo.lambarene@test.gab', 'groupe' => 'A+', 'quantite' => 1, 'urgence' => 'basse', 'lieu' => 'Laboratoire Albert Schweitzer', 'ville' => 'Lambaréné', 'statut' => 'active', 'date' => '2026-08-09 18:48:48'],
            ['user' => 'josiane.mouila@test.gab', 'groupe' => 'B+', 'quantite' => 2, 'urgence' => 'haute', 'lieu' => 'Hôpital de Mouila', 'ville' => 'Mouila', 'statut' => 'active', 'date' => '2026-08-09 18:48:48'],
            ['user' => 'josiane.mouila@test.gab', 'groupe' => 'O-', 'quantite' => 1, 'urgence' => 'moyenne', 'lieu' => 'Centre de Santé de Mouila', 'ville' => 'Mouila', 'statut' => 'active', 'date' => '2026-08-08 18:48:48'],
            ['user' => 'franck.koulamoutou@test.gab', 'groupe' => 'A+', 'quantite' => 3, 'urgence' => 'haute', 'lieu' => 'Hôpital de Koulamoutou', 'ville' => 'Koulamoutou', 'statut' => 'active', 'date' => '2026-08-08 18:48:48'],
            ['user' => 'franck.koulamoutou@test.gab', 'groupe' => 'B-', 'quantite' => 1, 'urgence' => 'basse', 'lieu' => 'Centre Médical Urbain', 'ville' => 'Koulamoutou', 'statut' => 'active', 'date' => '2026-08-07 18:48:48'],
            ['user' => 'banque.makokou@test.gab', 'groupe' => 'O+', 'quantite' => 5, 'urgence' => 'basse', 'lieu' => 'Banque de Sang de Makokou', 'ville' => 'Makokou', 'statut' => 'active', 'date' => '2026-08-07 18:48:48'],
            ['user' => 'banque.makokou@test.gab', 'groupe' => 'AB+', 'quantite' => 2, 'urgence' => 'moyenne', 'lieu' => 'Hôpital de Makokou', 'ville' => 'Makokou', 'statut' => 'active', 'date' => '2026-08-06 18:48:48'],
            ['user' => 'mireille.tchibanga@test.gab', 'groupe' => 'A-', 'quantite' => 2, 'urgence' => 'haute', 'lieu' => 'Hôpital de Tchibanga', 'ville' => 'Tchibanga', 'statut' => 'active', 'date' => '2026-08-06 18:48:48'],
            ['user' => 'mireille.tchibanga@test.gab', 'groupe' => 'O+', 'quantite' => 1, 'urgence' => 'moyenne', 'lieu' => 'Centre de Santé de Tchibanga', 'ville' => 'Tchibanga', 'statut' => 'active', 'date' => '2026-08-05 18:48:48'],
        ];

        foreach ($alertes as $a) {
            Alerte::firstOrCreate(
                [
                    'user_id' => User::where('email', $a['user'])->value('id'),
                    'groupe_sanguin_id' => $groupe($a['groupe']),
                    'lieu' => $a['lieu'],
                    'ville' => $a['ville'],
                ],
                [
                    'quantite' => $a['quantite'],
                    'urgence' => $a['urgence'],
                    'statut' => $a['statut'],
                    'created_at' => $a['date'],
                    'updated_at' => $a['date'],
                ]
            );
        }
    }
}
