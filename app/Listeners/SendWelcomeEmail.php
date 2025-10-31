<?php

namespace App\Listeners;

use App\Events\AdminRegister;
use App\Mail\WelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SendWelcomeEmail
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
    public function handle(AdminRegister $event): void
    {
        // $url = URL::temporarySignedRoute('verify.user', now()->addMinutes(2), ['id' => $event->user->id]);
        $url = URL::temporarySignedRoute(
            'verify.user',
            now('UTC')->addMinutes(10),
            ['id' => $event->user->id]
        );


        Mail::to($event->user->email)->send(new WelcomeMail($event->user, $url));
    }
}
