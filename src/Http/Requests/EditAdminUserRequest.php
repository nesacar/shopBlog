<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class EditAdminUserRequest extends FormRequest
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
    public function rules(Request $request)
    {
        if($request->password != null){
            return [
                'email' => 'required',
                'password' => 'confirmed',
                'role' => 'required|min:0'
            ];
        }else{
            return [
                'email' => 'required',
                'role' => 'required|min:0'
            ];
        }
    }

    public function messages()
    {
        return [
            'email.required' => 'Email adresa je obavezna.',
            'password.required' => 'Lozinka je obavezna.',
            'password.confirmed' => 'Niste dobro potvrdili lozinku, pokušajte ponovo.',
            'role.min' => 'Niste odabrali pravo pristupa'
        ];
    }
}
