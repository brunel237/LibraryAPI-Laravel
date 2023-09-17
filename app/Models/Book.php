<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "books";

    public function author(){
        return $this->belongsToMany(Author::class, 'book_authors');
    }
    public function bookCopy(){
        return $this->hasMany(BookCopy::class);
    }

    protected $fillable = [
        'type',
        'ISBN',
        'title',
        'editor',
        'production',
        'publish_date',
        'lending_price',
        'selling_price',
    ];
}
