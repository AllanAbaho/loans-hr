<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mailable', function () {
    $user = App\Models\User::find(1);

    return new App\Mail\NewUser($user);
});
