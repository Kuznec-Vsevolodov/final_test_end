<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueTeamEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;
    public $chat_id;

    public function __construct($id, $chat_id)
    {
        $this->id = $id;
        $this->chat_id = $chat_id;
    }

    public function broadcastOn()
    {
        return new Channel('team-queue-'.$this->id);
    }

    public function broadcastAs(){
        return 'team-queue-event';
    }
}
