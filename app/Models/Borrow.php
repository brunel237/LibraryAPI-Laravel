<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Borrow extends Model
{
    use HasFactory;
    use SoftDeletes;
//----------------------------------------------------------------------------------------------------
    protected $fillable = [
        'request_id',
        'expiring_date',
    ];
//----------------------------------------------------------------------------------------------------

    public function __construct($attributes = [],$request_id, $duration){
        parent::__construct($attributes);
        $this->request_id = $request_id;
        $this->expiring_date = now()->addDays($duration);
    }
    public function request(){
        return $this->belongsTo(Request::class);
    }

//----------------------------------------------------------------------------------------------------

    

}
