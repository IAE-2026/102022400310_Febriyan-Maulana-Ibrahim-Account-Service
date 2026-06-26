<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AccountController;

Route::prefix('v1')
    ->middleware('apikey')
    ->group(function () {

        Route::get('/', [AccountController::class, 'index']);

        Route::get('/accounts', [AccountController::class, 'index']);

        Route::get('/accounts/{id}', [AccountController::class, 'show']);

        Route::get('/{id}', [AccountController::class, 'show']);

        Route::post('/', [AccountController::class, 'store'])
            ->middleware('sso');

        Route::post('/accounts', [AccountController::class, 'store'])
            ->middleware('sso');

});
