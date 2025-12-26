<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DonationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/payments/midtrans/callback', [DonationController::class, 'midtransCallback'])
    ->middleware('throttle:60,1')
    ->name('payments.midtrans.callback');
