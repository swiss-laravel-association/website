<?php

use App\Http\Controllers\Api\NfcApiController;
use App\Http\Middleware\NfcAccessMiddleware;
use Illuminate\Support\Facades\Route;


Route::group([
        'prefix' => 'nfc',
        'as' => 'nfc',
        'middleware' => NfcAccessMiddleware::class
    ], function() {
        Route::get('client-connect', [NfcApiController::class, 'clientConnect']);
        Route::post('member-sign-in', [NfcApiController::class, 'memberSignIn']);
    });
