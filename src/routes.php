<?php

use App\Http\Controllers\Auth\PhoneVerificationController;
use Illuminate\Support\Facades\Route;

Route::get('/phone/verify', 'App\Http\Controllers\Auth\PhoneVerificationController@show')
    ->middleware('auth')->name('phone-verification.notice');
