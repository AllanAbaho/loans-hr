<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

Artisan::command('add:user', function () {
    $name = fake()->name();
    User::factory()->create([
        'name' => $name,
        'email' => fake()->unique()->safeEmail(),
        'password' => bcrypt('password'),
        'organization_id' => 1,
        'role' => 'Employee'
    ]);
    Log::info('Added new user ', ['name' => $name]);
})->purpose('Add new user to organisation')->everyFiveMinutes();
