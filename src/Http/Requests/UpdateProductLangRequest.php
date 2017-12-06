<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductLangRequest extends FormRequest
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
            'title' => 'required',
            'short' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Naziv proizvoda je obavezan.',
            'short.required' => 'Kratak opis je obavezan.'
        ];
    }
}
