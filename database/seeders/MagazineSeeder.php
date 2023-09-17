<?php

namespace Database\Seeders;

use App\Models\BookCopy;
use App\Models\Magazine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MagazineSeeder extends BookSeeder
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
                $book = Magazine::factory()->create();
                BookCopy::create([
                    'book_id' => $book->id,
                    'stock_quantity' => rand(20, 50),
                ])->save();
            });
        }
    }
}
