<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Mail\WelcomeMail;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Console\Migrations\RefreshCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function signin(Request $request){

        $validData = AuthRequest::signinRequest($request);

        $user = User::where('username', $validData['username'])->first();

        if (! $user || ! Hash::check($validData['password'], $user->password)){
            return response()->json(["message" => "Invalid Credentials"], 404);
        }

        $token = $user->createToken('UserToken')->accessToken;

        return response()->json(["user" => $user,  "token" => $token]);
    }

    public function signup(Request $request, $flag=null){

        $validData = AuthRequest::clientSignupRequest($request);
        $credentials = AuthRequest::userSignupRequest($request);

        if ($flag){
            $new_user = Client::newClient(array_merge($credentials, $validData));
        }
        else{
            $client = Client::where('email', $credentials['client_email'])->first();
            if (! $client || ! Hash::check($credentials['client_password'], $client->password) ){
                return response()->json(["message" => "Invalid Client Credentials"], 404);
            }
            $new_user = User::newUser($validData, $client);
        }

        Mail::to($new_user->client->email)->send(new WelcomeMail($new_user));

        return response()->json(["message" => "Sign Up Successfull", "user" => $new_user]);
    }

    // public function logout(){
    //     Auth::logout();

    //     return response()->json(["message"=>"Vous êtes déconnecté..."], 200);
    // }

}




