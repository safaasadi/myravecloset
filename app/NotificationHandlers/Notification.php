<?php

namespace App\NotificationHandlers;

use Illuminate\Support\Facades\Session;

abstract class Notification
{

    public $notification;

    public function __construct($notification) {
        $this->notification = $notification;
    }

    abstract public function getLink();

    abstract public function getHTML();

}