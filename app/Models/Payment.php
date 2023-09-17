<?php

namespace App\Models;

use App\Notifications\PaymentNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;

//----------------------------------------------------------------------------------------------------------
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'account_id',
        'amount',
    ];

//-----------------------------------------------------------------------------------------------------------------
    public static function booted(){
        static::creating(function($payment){
            $account_id = str_pad($payment->account_id, 4, 0, STR_PAD_LEFT);
            $user_id = str_pad($payment->account->user_id, 6, 0, STR_PAD_LEFT);

            $payment->id = now()->toDateString()."-".now()->toTimeString()."-{$account_id}-{$user_id}";

        });

        static::saved(function($payment) {
            $user = $payment->account->user->first();
            //  $userDept = $user->deptStatus()->first();
            $userDept = DeptStatus::where('user_id', $user->id)->first();
            if ($userDept){
                $new_amount = $payment->amount - $userDept->amount_left;
                if ($new_amount < 0){
                    $payment->update(['amount' => 0]);
                    $userDept->update(['amount_left' => abs($new_amount)]);
                    $user->notify(new PaymentNotification($payment->id, $payment->amount, abs($new_amount)));
                }
                else{
                    $payment->update(['amount' => $new_amount]);
                    $userDept->delete();
                    $user->notify(new PaymentNotification($payment->id, $userDept->amount_left, 0));
                }
            }
        });
    }

    public function account(){
        return $this->belongsTo(Account::class);
    }
    public function request(){
        return $this->belongsTo(Request::class);
    }

    public function getAmount(){
        return $this->amount;
    }


}
