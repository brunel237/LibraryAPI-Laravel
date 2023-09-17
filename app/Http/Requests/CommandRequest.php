<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommandRequest extends FormRequest
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
            'book_id'     => "required|numeric|min:1",
            'payment_id   => "required|string|min:10"',
            'action'      => "required|in:borrow, purchase",
            'quantity'    => "required|numeric|min:1",
            // 'status'      => "",
        ];
    }
}
