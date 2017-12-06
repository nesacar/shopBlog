<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLanguageRequest extends FormRequest
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
            'name' => 'required',
            'fullname' => 'required',
            'locale' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Naziv jezika je obavezan.',
            'fullname.required' => 'Pun naziv jezika je obavezan.',
            'locale.required' => 'Lokalozacija je obavezna.',
        ];
    }
}
