<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AccountController;

Route::prefix('v1')
    ->middleware('apikey')
    ->group(function () {

        Route::get('/accounts', [AccountController::class, 'index']);

        Route::get('/accounts/{accountNumber}', [AccountController::class, 'show']);

        Route::post('/accounts', [AccountController::class, 'store'])
            ->middleware('sso');

});