<?php

use Illuminate\Support\Facades\Route;

use App\Models\Consumptions;
use App\Models\Prices;

Route::get('/', function () {

    return view('app');
});

Route::get('/dataviewer', function () {
    $data = [
        'prices'        => Prices::get(),
        'consumptions'  => Consumptions::get(),
    ];

    return view('/dataviewer', compact('data'));
});


