<?php

namespace Database\Seeders;

use App\Models\BookCopy;
use App\Models\TextBook;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TextBookSeeder extends BookSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 20) as $i){
            DB::transaction(function(){
                $book = TextBook::factory()->create();
                BookCopy::create([
                    'book_id' => $book->id,
                    'stock_quantity' => rand(20, 50),
                ])->save();
            });
        }
    }
}
