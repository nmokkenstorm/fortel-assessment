<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexClient extends FormRequest
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
     * We want query string parameters too
     *
     * @return array
     */
    public function all($keys = null)
    {
        return array_merge(
            parent::all(),
            [$this->input('page'), $this->input('limit')]
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page'      => 'min:1|numeric',
            'limit'     => 'min:1|numeric|max:255',
        ];
    }
}
