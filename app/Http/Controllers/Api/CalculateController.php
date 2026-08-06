<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
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
                /*if(...){
                    return response()->json(['Error' => 'Dates range not valid'],404);
                }*/

                //resultado de aplicar la formula
                $price_indexed = $expression->evaluate($request->formula, [
                    //'salario' => 2000,
                    //'bono' => 500,
                ]);
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
            return response()->json(['Error' => 'Unexpected error'], 500);
        }
    }
}
