<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
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
            'image' => 'image|mimes:jpg,png,jpeg',
            'publish_at' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'image.image' => 'Slika nije slika',
            'image.mimes' => 'Slika nije u jpg, png ili jpeg formatu',
            'publish_at.required' => 'Datum publikovanja je obavezan.',
        ];
    }
}
