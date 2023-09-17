<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookCopy extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function book(){
        return $this->belongsTo(Book::class);
    }
    public function resetStockQuantity($amount){
        $this->update([
            'stock_quantity' => $this->stock_quantity - $amount
        ]);
        // return json_encode([
        //     "status" => true,
        //     "message" => "success"
        // ]);
    }

    protected $fillable = [
        'book_id',
        'stock_quantity',
    ];
}
