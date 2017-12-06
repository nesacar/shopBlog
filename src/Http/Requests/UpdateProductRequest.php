<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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

    public function rules()
    {
        return [
            'publish_at' => 'required',
            'img' => 'image|mimes:jpeg,jpg,png',
            'price_small' => 'required|numeric|min:1',
            'kat' => 'required',
            'code' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'publish_at.required' => 'Datum objave proizvoda je obavezan.',
            'price_small.required' => 'Maloprodajna cena je obavezna.',
            'price_small.numeric' => 'Maloprodajna cena mora biti broj.',
            'price_small.min' => 'Maloprodajna cena mora biti pozitivan broj.',
            'img.image' => 'Uploudovani fajl mora biti slika.',
            'img.mimes' => 'Slika mora biti u jpeg, jpg ili png formatu.',
            'kat.required' => 'Jedna kategorija je obavezna.',
            'code.required' => 'Šifra je obavezna.',
        ];
    }
}
