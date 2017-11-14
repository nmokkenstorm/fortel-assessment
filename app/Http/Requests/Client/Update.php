<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
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
            'first_name'    => 'max:255|alpha_dash|string|min:1',
            'last_name'     => 'max:255|alpha_dash|string|min:1',
            'email'         => 'max:255|email|string', 
        ];
    }
}
