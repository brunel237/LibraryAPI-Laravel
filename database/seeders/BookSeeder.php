<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    }

    // public function linkBookToAuthor(Book $book){
    //     $arr = Author::pluck('id')->toArray();
    //     $chunk_arr = array_chunk($arr, rand(1, count($arr)));
    //     $book->author()->attach($chunk_arr[rand(0, count($chunk_arr)-1)]);
    // }
}

