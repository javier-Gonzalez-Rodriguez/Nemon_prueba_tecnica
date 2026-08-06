<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Consumptions;

class ConsumptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fecha_inicio = Carbon::create(2025, 1, 1);

        for ($i = 0; $i < 365; $i++) {

            $registro = [
                'date' => $fecha_inicio->copy()->addDays($i)->format('Y-m-d'),
            ];

            for ($h = 1; $h <= 25; $h++) {
                $registro["h{$h}"] = round(mt_rand(10, 250) / 10, 2);
            }

            DB::table('consumptions')->insert($registro);
        }
    }
}
