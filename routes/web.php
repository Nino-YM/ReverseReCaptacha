<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/validate-recaptcha', function (Illuminate\Http\Request $request) {
    if ($request->has('recaptcha-checkbox')) {
        return 'Bot verified successfully';
    }

    return 'Verification failed';
});