<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
            'type'          => "required|string|in:TextBook,Magazine,Roman",
            'ISBN'          => "required|string|min:10",
            'title'         => "required|string|",
            'editor'        => "required|string|min:2",
            'production'    => "required|string|min:2",
            'publish_date'  => "required|date",
            'lending_price' => "required|numeric|min:300",
            'selling_price' => "required|numeric|min:300",
            'authors'       => "required|array",
            'stock_quantity'=> "required|numeric|min:1",
        ];
    }
}
