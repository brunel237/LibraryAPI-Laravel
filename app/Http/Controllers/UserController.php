<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\CommandRequest;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\UserRequest;
use App\Models\Account;
use App\Models\Request as ModelsRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users =  User::all();

        return response()->json(["users" => $users]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::find($id);

        if ($user){
            $user->update($request->toArray());
            return response()->json(["message" => "User updated successfully"]);
        }

        return response()->json(["message" => "User not found"], 404);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user){
            $user->delete(); $user->delete();
            return response()->json(["message" => "User deleted successfully"]);
        }

        return response()->json(["message" => "User not found"], 404);

    }

    public function notifications(int $id){

        $notification = DB::select('select * from notifications where notifiable_id = ?', [$id]);
        return $notification;
    }

    public function newPayment(PaymentRequest $request){
        $account = Account::where('account_number', $request['account_number'])->first();

        if ($account && $account->user_id == auth()->user()->id){
            //DB::beginTransaction();
            $ans = $account->initiatePayment($request['amount']);

            if (json_decode($ans)->status){
                return response()->json(["message" => "Payment initiated successfully.", "payment_id" => json_decode($ans)->payment->id]);
            }
            else{
                return response()->json(["message" => json_decode($ans)->message], 400);
            }
        }

        return response()->json(["message" => "Unknown Account"], 404);

    }


}
