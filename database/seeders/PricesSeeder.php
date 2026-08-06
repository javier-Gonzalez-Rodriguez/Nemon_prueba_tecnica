<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Prices;

class PricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fecha_inicio = Carbon::create(2025, 1, 1);

        for ($d = 0; $d < 365; $d++) {

            $registro = [
                'date' => $fecha_inicio->copy()->addDays($d)->format('Y-m-d'),
            ];

            for ($h = 1; $h <= 25; $h++) {

                $base = 0.12;
                $picoManana = exp(-pow(($h - 10) / 2.5, 2)) * 0.04;
                $picoTarde = exp(-pow(($h - 20) / 2.8, 2)) * 0.07;
                $variacionDia = mt_rand(-10, 10) / 1000;
                $ruido = mt_rand(-5, 5) / 1000;

                $registro["h{$h}"] = round(
                    max(0.04, $base + $picoManana + $picoTarde + $variacionDia + $ruido),
                    4
                );
            }

            // Aquí se guarda el registro completo de ese día
            DB::table('prices')->insert($registro);
        }
    }
}
