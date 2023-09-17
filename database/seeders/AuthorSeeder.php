<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 45) as $i){
            DB::transaction(function(){
                $author = Author::factory()->create();
                $this->linkBookToAuthor($author);
            });

        }
    }

    public function linkBookToAuthor(Author $author){
        $arr = Book::pluck('id')->toArray();
        $chunk_arr = array_chunk($arr, rand(1, 4));
        $author->book()->attach($chunk_arr[rand(0, 5)]);
    }
}
