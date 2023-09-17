<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function __construct($attributes = [],$request_id, $amount){
        parent::__construct($attributes);
        $this->request_id = $request_id;
        $this->amount = $amount;
    }

    public function request(){
        return $this->belongsTo(Request::class);
    }

    protected $fillable = [
        'request_id',
        'amount',
    ];


}
