<?php

use App\Http\Controllers\AuthController;
use App\Mail\NewUser;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mailable', function () {
    $user = User::find(1);

    return new NewUser($user);
});

Route::controller(AuthController::class)->group(function () {
    Route::get('login/{provider}', 'redirectToProvider');
    Route::get('login/{provider}/callback', 'handleProviderCallback');
});
