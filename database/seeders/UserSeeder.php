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
        // Buscar personas del padrón municipal para asociar los usuarios clave
        $personRoot = Person::where('document_number', '10783864')->first() // Jaime Henry Sandoval Nieves (Jefe ODT)
            ?? Person::where('office', 'like', '%[ODT]%')->first();

        $personSupport = Person::where('document_number', '00328619')->first() // Juan Kehuarucho Saavedra (OACP)
            ?? Person::where('office', 'like', '%[OACP]%')->first();

        $personTransport = Person::where('document_number', '02830165')->first() // Gustavo Adolfo Paredes Shimokawa (STTV)
            ?? Person::where('office', 'like', '%[STTV]%')->first();

        $users = [
            [
                'username' => 'root',
                'password' => Hash::make('admin123'),
                'full_name' => 'Administrador del Sistema SIGM',
                'email' => 'admin@castilla.gob.pe',
                'role' => 'Administrador Central',
                'allowed_modules' => ['admin', 'itam', 'transportes', 'helpdesk'],
                'is_active' => true,
                'person_id' => $personRoot?->id,
            ],
            [
                'username' => 'jperez',
                'password' => Hash::make('admin123'),
                'full_name' => 'Juan Alberto Pérez Morales',
                'email' => 'jperez@sigm.gob.pe',
                'role' => 'Técnico de Soporte e ITAM',
                'allowed_modules' => ['admin', 'itam', 'helpdesk'],
                'is_active' => true,
                'person_id' => $personSupport?->id,
            ],
            [
                'username' => 'mgomez',
                'password' => Hash::make('admin123'),
                'full_name' => 'María Elena Gómez Castillo',
                'email' => 'mgomez@sigm.gob.pe',
                'role' => 'Operador de Transportes',
                'allowed_modules' => ['transportes', 'helpdesk'],
                'is_active' => true,
                'person_id' => $personTransport?->id,
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
