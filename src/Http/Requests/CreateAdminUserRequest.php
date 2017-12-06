<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdminUserRequest extends FormRequest
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
            'email' => 'required|unique:users',
            'password' => 'required|confirmed',
            'role' => 'required|min:0'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Ime je obavezno.',
            'lastname.required' => 'Prezime je obavezno.',
            'email.required' => 'Email adresa je obavezna.',
            'password.required' => 'Lozinka je obavezna.',
            'username.unique' => 'Korisni?ko ime je zauteto, pokušajte sa drugim.',
            'email.unique' => 'Email adresa je zauteta. Pokušajte sa drugom.',
            'password.confirmed' => 'Niste dobro potvrdili lozinku, pokušajte ponovo.',
            'role.min' => 'Niste odabrali pravo pristupa'
        ];
    }
}
