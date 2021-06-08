<?php

namespace App\Events;

use App\ModelImage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Message;

class NewMessage implements ShouldBroadcast {
    
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message) {
        $this->message = $message;
    }


    public function broadcastOn()
    {
        return ['Chat.' . $this->message->to_user_id];
    }
}