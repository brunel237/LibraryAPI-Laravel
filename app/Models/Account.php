<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Account extends Model
{
    use HasFactory;
    use SoftDeletes;

//----------------------------------------------------------------------------------------------------------------

    protected $fillable = [
        'user_id',
        'balance',
        'status',
        'account_number',
    ];

    public static function booted(){
        static::creating(function ($account) {
            $account->account_number = now()->toDateString()."-".now()->toTimeString()."-{$account->user_id}-{$account->id}";
        });
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function payment(){
        return $this->belongsToMany(Payment::class);
    }
    public function getAccountNumber(){
        return $this->account_number;
    }
//-----------------------------------------------------------------------------------------------------------

    public function creditAccount($amount){

        $this->update([
            'balance' => $this->balance + $amount
        ]);

        return json_encode([
            "status" => true,
            "message" => "success"
        ]);
    }

//---------------------------------------------------------------------------------------------------------------
    public function discreditAccount($amount){

        $difference = $this->balance - $amount;

        if ($difference < 0){
            return json_encode([
                "status" => false,
                "message" => "Insufficient balance"
            ]);
        }

        $this->update([
            'balance' => $difference
        ]);

        return json_encode([
            "status" => true,
            "message" => "success"
        ]);
    }

//-----------------------------------------------------------------------------------------------------------
    public function initiatePayment($amount){

        if ($this->balance < $amount)
            return json_encode([
                "status" => false,
                "message" => "Insufficient balance"
            ]);
        else{
            return $res = DB::transaction(function() use($amount) {
                try{
                    $ans  = $this->discreditAccount($amount);

                    if (! json_decode($ans, true)['status']) return  $ans;

                    $payment = Payment::create([
                        'account_id' => $this->id,
                        'amount' => $amount,
                    ]);

                    $ans = json_decode($ans, true);
                    $ans['payment'] = $payment;
                    $ans = json_encode($ans);

                    DB::commit();

                    return $ans;
                }
                catch (Exception $e){
                    DB::rollBack();
                    return json_encode([
                        "status" => false,
                        "message" => "Transaction error (Payment Initiation) => ".$e
                    ]);
                }
            });
        }

    }

}
