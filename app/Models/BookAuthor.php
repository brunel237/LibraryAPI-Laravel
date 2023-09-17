<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookAuthor extends Model
{
    use HasFactory;
    use SoftDeletes;

    // protected $table = "book_authors";

    protected $fillable = [
        'book_id',
        'author_id',
    ];
}
