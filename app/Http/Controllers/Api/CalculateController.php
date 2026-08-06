<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Carbon\Carbon;
use Throwable;
use Exception;

use App\Models\Consumptions;
use App\Models\Prices;

class CalculateController extends Controller
{
    /**
     * Calcula el precio indexado de energía para un periodo determinado (start_date -
     * end_date) usando una fórmula configurable. 
     * 
     * @return Double Devuelve el resultado del cálculo
     */
    public function calculate(Request $request){

        try{
            $expression = new ExpressionLanguage();
            $price_indexed = null;

            try{
                if($request->start_date == null || $request->end_date == null){
                    throw new Exception('Invalid date');
                }

                $dates_range_validator_prices = Prices::whereBetween('date', [
                    $request->start_date,
                    $request->end_date,
                ])->pluck('date');

                $dates_range_validator_consumptions = Consumptions::whereBetween('date', [
                    $request->start_date,
                    $request->end_date,
                ])->pluck('date');

                $inicio = Carbon::parse($request->start_date);
                $fin    = Carbon::parse($request->end_date);

                $expectedDays = $inicio->diffInDays($fin) + 1;

                if($dates_range_validator_prices->count() != $expectedDays || $dates_range_validator_consumptions->count() != $expectedDays){
                    return response()->json(['Error' => 'Dates range not valid'],404);
                }

                $columnas = [];

                for ($i = 1; $i <= 25; $i++) {
                    $columnas[] = "SUM(h{$i})";
                }
                



                $datos_precios = Prices::whereBetween('date', [
                    $request->start_date,
                    $request->end_date,
                ])->get();




                $consumption_value  = 0;


                $formula_lista = str_replace(['[', ']'], '', $request->formula);
                $suma_importes = 0;

                foreach($datos_precios as $value){
                    foreach(json_decode($value) as $key => $precio){
                        if($key != 'date'){
                            //sumatoria de importe hora
                            $suma_importes += $expression->evaluate($formula_lista, [
                                'OMIE_MD' => $precio,
                            ]);
                        }
                    }

                }

                $suma_consumos = Consumptions::whereBetween('date', [
                    $request->start_date,
                    $request->end_date,
                ])->selectRaw(
                    implode(' + ', $columnas) . 'as total'
                )->value('total');


                //NOTA: no se a cuantos decimales se deberia de redondear
                $price_indexed = $suma_importes / $suma_consumos;

                //si se quisiera rendondear descomentar siguente linea la cual redondeará a 2 decimales
                //$price_indexed = round($price_indexed, 2);

            }catch(Exception $e){
                Log::error('Error al evaluar expresion', [
                    'mensaje' => $e->getMessage(),
                    'archivo' => $e->getFile(),
                    'linea' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return response()->json(['Error' => 'Invalid data'], 400);
            }


            return response()->json(['price_indexed' => $price_indexed], 200);
        }catch(Throwable $e){
                Log::error('Error al evaluar expresion', [
                    'mensaje' => $e->getMessage(),
                    'archivo' => $e->getFile(),
                    'linea' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
            return response()->json(['Error' => 'Unexpected error'], 500);
        }
    }
}
