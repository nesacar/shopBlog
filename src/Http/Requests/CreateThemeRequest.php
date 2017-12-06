<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateThemeRequest extends FormRequest
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
            'version' => 'required',
            'developer' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Naziv je obavezan.',
            'version.required' => 'Verzija je obavezan.',
            'developer.required' => 'Programer je obavezan.',
        ];
    }
}
