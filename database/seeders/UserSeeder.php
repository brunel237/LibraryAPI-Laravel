<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
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
                $user = User::factory()->create();
                $account = Account::create([
                    'user_id' => $user->id,
                    'balance' => 0.0
                ])->save();
            });
        }
        
    }
}
