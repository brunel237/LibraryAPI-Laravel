<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeptStatus extends Model
{
    use HasFactory;
    use SoftDeletes;
//----------------------------------------------------------------------------------------------------
    protected $fillable = [
        'user_id',
        'amount_left',
    ];
//----------------------------------------------------------------------------------------------------

    public function user(){
        return $this->belongsTo(User::class);
    }


    public function __construct($attributes = [], $user_id=null,  $difference=null){
        parent::__construct($attributes);
        $this->user_id = $user_id;
        $this->amount_left = $difference;
    }





}
