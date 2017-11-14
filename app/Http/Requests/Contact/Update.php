<?php

namespace App\Http\Requests\Contact;

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
            'address'       => 'max:255|string|min:1',
            'postcode'      => 'max:7|string|regex:/^[\d]{4}\s?[a-zA-Z]{2}$/',
        ];
    }
}
