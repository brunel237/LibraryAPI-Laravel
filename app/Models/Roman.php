<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roman extends Book
{
    use HasFactory;

    protected static function booted(){
        static::creating(function($roman) {
            $roman->type = "Roman";
        });
    }
}
