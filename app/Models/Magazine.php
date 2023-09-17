<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magazine extends Book
{
    use HasFactory;

    protected static function booted(){
        static::creating(function($magazine) {
            $magazine->type = "Magazine";
        });
    }
}
