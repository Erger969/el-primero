<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Career;
use Illuminate\Support\Facades\Hash;

class RolesAndAdminSeeder extends Seeder
{
    public function run()
    {
        // Crear usuario administrador prime
        $admin = User::create([
            'name' => 'Admin',
            'lastname' => 'Principal',
            'email' => 'admin@universidad.com',
            'password' => Hash::make('admin123'),
            'role_id' => 3,  // Admin prime
            'descripcion' => 'Administrador del sistema',
            'career_id' => Career::first()->id,  // Toma la primera carrera
        ]);

        // Crear usuario master de ejemplo
        $master = User::create([
            'name' => 'Master',
            'lastname' => 'Ejemplo',
            'email' => 'master@universidad.com',
            'password' => Hash::make('master123'),
            'role_id' => 2,  // Master
            'descripcion' => 'Usuario verificado de confianza',
            'career_id' => Career::first()->id,
        ]);

        // Crear usuario universitario de ejemplo
        $user = User::create([
            'name' => 'Usuario',
            'lastname' => 'Normal',
            'email' => 'usuario@universidad.com',
            'password' => Hash::make('user123'),
            'role_id' => 1,  // Universitario
            'descripcion' => 'Estudiante universitario',
            'career_id' => Career::first()->id,
        ]);

        $this->command->info('Usuarios creados:');
        $this->command->info('Admin: admin@universidad.com / admin123');
        $this->command->info('Master: master@universidad.com / master123');
        $this->command->info('Usuario: usuario@universidad.com / user123');
    }
}