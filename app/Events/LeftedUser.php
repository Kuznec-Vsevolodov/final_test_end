<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeftedUser implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_name;
    public $id;

    public function __construct($user_name, $id)
    {
        $this->user_name = $user_name;
        $this->id = $id;
    }

    public function broadcastOn()
    {
        return new Channel('lefted-users-'.$this->id);
    }

    public function broadcastAs()
    {
        return 'lefted-users-event';
    }
}
