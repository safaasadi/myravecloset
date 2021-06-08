<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'handler',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function getHandler() {
        $class = $this->handler; 
        try {
            $instance = new $class($this);
            return $instance;
        } catch(\Exception $e) {
            $this->delete();
        }
        return null;
    }

    public function getHTML() {
        $handler = $this->getHandler();
        if($handler != null) return $handler->getHTML();
        return null;
    }


    public function getLink() {
        $handler = $this->getHandler();
        if($handler != null) return $handler->getLink();
        return null;
    }

}
