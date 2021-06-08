<?php

namespace App\Events;

use App\ModelImage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Notification;

class NewNotification implements ShouldBroadcast {
    
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;
    public $html;
    public $link;

    public function __construct(Notification $notification) {
        $this->notification = $notification;
        $this->html = $notification->getHTML();
        $this->link = $notification->getLink();
    }

    public function broadcastOn()
    {
        return ['Notification.' . $this->notification->user_id];
    }
}