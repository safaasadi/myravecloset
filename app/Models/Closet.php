<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Closet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function getOwner() {
        return \App\Models\User::where('id', $this->user_id)->first();
    }

    public function getProducts() {
        return \App\Models\Product::where('closet_id', $this->id)->get();
    }
}
