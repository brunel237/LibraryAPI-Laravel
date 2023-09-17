<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function book(){
        return $this->belongsToMany(Book::class, 'book_authors');
    }

    protected $fillable = [
        'name',
        'email',
        'address',
        'biography',
    ];


}
