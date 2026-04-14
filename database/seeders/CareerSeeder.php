<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Career;

class CareerSeeder extends Seeder
{
    public function run()
    {
        $carreras = [
            ['nombre' => 'Ingeniería Informática', 'facultad' => 'Tecnología'],
            ['nombre' => 'Administración de Empresas', 'facultad' => 'Ciencias Económicas'],
            ['nombre' => 'Derecho', 'facultad' => 'Ciencias Jurídicas'],
            ['nombre' => 'Medicina', 'facultad' => 'Ciencias de la Salud'],
            ['nombre' => 'Arquitectura', 'facultad' => 'Diseño y Construcción'],
            ['nombre' => 'Psicología', 'facultad' => 'Ciencias Sociales'],
            ['nombre' => 'Comunicación Social', 'facultad' => 'Humanidades'],
            ['nombre' => 'Ingeniería Civil', 'facultad' => 'Tecnología'],
        ];

        foreach ($carreras as $carrera) {
            Career::create($carrera);
        }
    }
}