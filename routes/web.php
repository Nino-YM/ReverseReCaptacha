<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/validate-recaptcha', function (Illuminate\Http\Request $request) {
    if ($request->has('recaptcha-checkbox')) {
        return response()->view('matrix', [], 200)->header('Content-Type', 'text/html');
    }

    return 'Verification failed';
});
