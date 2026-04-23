<?php

namespace App\Listeners;

use App\Events\OrderNotificationsEvent;
use App\Notifications\BrandNotification;
use App\Notifications\Notifiable;
use App\Notifications\QuotationNotification;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class OrderNotificationsListener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderNotificationsEvent $event)
    {
        $users = $event->notification['userId'];
        
        foreach ($users as $key => $userid) {
            // dd($userid);
            User::where('id', $userid)->get()->each(function(User $user) use ($event){
                Notification::send($user, new Notifiable($event->notification));
            });
        }

    }
}
