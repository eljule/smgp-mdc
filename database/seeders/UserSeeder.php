<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $personMendoza = Person::where('document_number', '08192844')->first();
        $personPerez = Person::where('document_number', '45821903')->first();
        $personGomez = Person::where('document_number', '40192837')->first();

        $users = [
            [
                'username' => 'root',
                'password' => Hash::make('admin123'),
                'full_name' => 'Administrador del Sistema SIGM',
                'email' => 'admin@castilla.gob.pe',
                'role' => 'Administrador Central',
                'allowed_modules' => ['admin', 'itam', 'transportes', 'helpdesk'],
                'is_active' => true,
                'person_id' => $personMendoza?->id,
            ],
            [
                'username' => 'jperez',
                'password' => Hash::make('admin123'),
                'full_name' => 'Juan Alberto Pérez Morales',
                'email' => 'jperez@sigm.gob.pe',
                'role' => 'Técnico de Soporte e ITAM',
                'allowed_modules' => ['admin', 'itam', 'helpdesk'],
                'is_active' => true,
                'person_id' => $personPerez?->id,
            ],
            [
                'username' => 'mgomez',
                'password' => Hash::make('admin123'),
                'full_name' => 'María Elena Gómez Castillo',
                'email' => 'mgomez@sigm.gob.pe',
                'role' => 'Operador de Transportes',
                'allowed_modules' => ['transportes', 'helpdesk'],
                'is_active' => true,
                'person_id' => $personGomez?->id,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['username' => $userData['username']],
                $userData
            );
        }
    }
}
