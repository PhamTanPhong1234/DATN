<?php

namespace App\Listeners;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogLoginDetails
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
    public function handle(object $event): void
    {
        $user = $event->user;
        $ip = Request::ip();
        $userAgent = Request::header('User-Agent');
        DB::table('login_logs')->insert([
            'user_id' => $user->id,
            'ip_address' => $ip,
            'device' => $userAgent,
            'created_at' => now(),
        ]);
    }
}
