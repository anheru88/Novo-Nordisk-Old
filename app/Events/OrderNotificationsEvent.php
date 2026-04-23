<?php

namespace App\Events;

use App\Brands;
use App\Negotiation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderNotificationsEvent implements ShouldBroadcastNow
{
    public $notification = [];
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return ['novo-channel.' . $this->notification['idUser'], 'novo-channel'];
        return new Channel('novo-channel');
    }

   /* public function broadcastWith()
    {
        // $extra = [
        //     'brands_name' => $this->brands->status->name,
        //     'brands_percent' => $this->order->status->percent,
        // ];
        return array_merge($this->notification->toArray());
    }*/
}
