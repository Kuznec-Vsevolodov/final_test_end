<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $id;
    public $type;

    public function __construct($id, $message, $type)
    {
        $this->message = $message;
        $this->id = $id;
        $this->type = $type;
    }

    public function broadcastOn()
    {
        return new Channel($this->type.'-chat-'.$this->id);
    }
    public function broadcastAs()
    {
        return 'chat-event';
    }
}
