<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use App\Mail\UserLoginMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendLoginNotification  implements ShouldQueue
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
    public function handle(UserLoggedIn $event): void
    {
          Mail::to($event->user->email)->send(new UserLoginMail($event->user));
    }
}
