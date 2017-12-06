<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUsersRequest extends FormRequest
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
            //
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email adresa je obavezna.',
            'password.required' => 'Lozinka je obavezna.',
            'username.unique' => 'Korisni?ko ime je zauteto, poku�ajte sa drugim.',
            'email.unique' => 'Email adresa je zauteta. Poku�ajte sa drugom.',
            'password.confirmed' => 'Niste dobro potvrdili lozinku, poku�ajte ponovo.',
        ];
    }
}
