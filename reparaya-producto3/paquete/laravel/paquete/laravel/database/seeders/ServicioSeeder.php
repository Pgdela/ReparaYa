<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            ['nombre' => 'Fontanería', 'precio_base' => 60.00],
            ['nombre' => 'Electricidad', 'precio_base' => 55.00],
            ['nombre' => 'Cerrajería', 'precio_base' => 70.00],
            ['nombre' => 'Climatización', 'precio_base' => 80.00],
        ];

        foreach ($servicios as $servicio) {
            Servicio::updateOrCreate(
                ['nombre' => $servicio['nombre']],
                [
                    'precio_base' => $servicio['precio_base'],
                    'activo' => true,
                ]
            );
        }
    }
}
