<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatUsersTeamEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $main_user;
    public $id;

    public function __construct($main_user, $id)
    {
        $this->main_user = $main_user;
        $this->id = $id;
    }

    public function broadcastOn()
    {
        return new Channel('chat-users-team-'.$this->id);
    }

    public function broadcastAs(){
        return 'chat-users-event';
    }
}
