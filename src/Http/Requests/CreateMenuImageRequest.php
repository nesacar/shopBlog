<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMenuImageRequest extends FormRequest
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
            'button' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Naziv slike je obavezan.',
            'button.required' => 'Tekst dugmeta je obavezan.'
        ];
    }
}
