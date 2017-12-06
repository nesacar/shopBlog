<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePCategoryRequest extends FormRequest
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
            'desc' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Tekst članka je obavezan.',
            'desc.required' => 'Skraćeni tekst je obavezan.'
        ];
    }
}
