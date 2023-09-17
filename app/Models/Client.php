<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;


    protected $fillable = [
        'first_name',
        'last_name',
        'sex',
        'date_of_birth',
        'email',
        'password',
        'phone_number',
        'address',
        'profession',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
//-------------------------------------------------------------------------------------------------

    public static function newClient($data_array){

        return DB::transaction(function() use($data_array) {
            $client = Client::create([
                'first_name' => $data_array['first_name'],
                'last_name' => $data_array['last_name'],
                'sex' => $data_array['sex'],
                'date_of_birth' => $data_array['date_of_birth'],
                'email' => $data_array['email'],
                'password' => $data_array['password'],
                'phone_number' => $data_array['phone_number'],
                'address' => $data_array['address'],
                'profession' => $data_array['profession'],
            ]);
            return User::newUser($data_array, $client);
        });

    }

}
