<?php

namespace App\Listeners;

use App\Events\NewUserAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class VerifyEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewUserAdded $event): void
    {
        $user = $event->user;
        $user->email_verified_at = now();
        $user->save();
        Log::info("Email verified at " . now());
    }
}
