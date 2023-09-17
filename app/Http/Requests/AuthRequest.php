<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public static function signinRequest(Request $request){

        return $request->validate([
            'username' => "required",
            'password' => "required"
        ]);

    }

    public static function clientSignupRequest(Request $request){

        return $request->validate([
            'first_name' => "required|min:2|string|alpha",
            'last_name'  => "required|min:2|string|alpha",
            'sex' => "required|in:male,female",
            'date_of_birth' => "required|date|date_format:Y-m-d",
            'email' => "required|email",
            'password' => "required|min:6|confirmed",
            'phone_number' => "required|string|numeric|min:6",
            'address' => "required|string|min:3",
            'profession' => "required|string|min:3",
        ]);

    }

    public static function userSignupRequest(Request $request){

        return $request->validate([
            'client_email' => "required|email",
            'client_password' => "required",
            'username' => "required|unique:users,username|min:4",
            'password' => "required|min:6|confirmed",
        ]);
    }

}
