<?php

namespace App\Models;

use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use PhpParser\Node\Expr\Cast\Double;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;
    use Notifiable;

    protected $fillable = [
        'username',
        'password',
        'role',
        'isEligible',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

//-----------------------------------------------------------------------------------------------------------
    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function account(){
        return $this->hasMany(Account::class);
    }
    public function request(){
        return $this->hasMany(Request::class);
    }
    public function deptStatus(){
        return $this->hasOne(DeptStatus::class);
    }


    // public function getEligibility(){
    //     return $this->isEligible;
    // }
    // public function setEligibility($status){
    //     $this->update([
    //         'isEligible' => $status
    //     ]) ;
    // }
//-----------------------------------------------------------------------------------------------------------

        public static function newUser($data_array, $client){

            return DB::transaction(function() use ($data_array, $client) {
                $user = User::create([
                    'username' => $data_array['username'],
                    'password' => $data_array['password'],
                    'role' => "user",
                    'isEligible' => true,
                    'client_id' => $client->id,
                ]);
                $user->createUserAccount();
                return $user;
            });
        }
    // private function checkAccount($account_number){
    //     $account = $this->account()->where('account_number', $account_number)->get(['*'])->first();

    //     if (! $account) return false;
    //     return $account;
    // }
//-----------------------------------------------------------------------------------------------------------

        public function createUserAccount(){
            Account::create([
                'user_id' => $this->id,
                'balance' => 0.0
            ]);
        }
    public function borrowArticle($book, $payment, $quantity){

        Request::create([
            'user_id' => $this->id,
            'book_id' => $book,
            'payment_id' => $payment,
            'action' => "borrow",
            'quantity' => $quantity,
        ]);

        return json_encode([
            "status" => true,
            "message" => "success"
        ]);

    }
//---------------------------------------------------------------------------------------

    public function buyArticle($book, $payment, $quantity){

        Request::create([
            'user_id' => $this->id,
            'book_id' => $book,
            'payment_id' => $payment,
            'action' => "purchase",
            'quantity' => $quantity,
        ]);

        return json_encode([
            "status" => true,
            "message" => "success"
        ]);
    }
//--------------------------------------------------------------------------------------------------------------------------
    public static function transactionHistory($user = null){

    }
//--------------------------------------------------------------------------------------------------------------------------

    // public function transactionHistory(){

    // }
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------



}

