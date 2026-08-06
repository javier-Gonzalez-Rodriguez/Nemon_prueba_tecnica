<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CalculateController;

Route::post('/calculate', [CalculateController::class, 'calculate']);