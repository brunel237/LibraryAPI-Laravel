<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookAuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'book_id' => $this->faker->randomElement(Book::pluck('id')->toArray()),
            'author_id' => $this->faker->randomElement(Author::pluck('id')->toArray()),
        ];
    }

    public function linkBookToAuthor(Book $book){
        $arr = Author::pluck('id')->toArray();
        $chunk_arr = array_chunk($arr, rand(1, count($arr)));
        $book->author()->attach($chunk_arr[rand(0, count($chunk_arr)-1)]);
    }
}
