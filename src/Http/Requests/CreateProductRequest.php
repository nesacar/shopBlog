<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'publish_at' => 'required',
            'short' => 'required',
            'img' => 'image|mimes:jpeg,jpg,png',
            'price_small' => 'required|numeric|min:1',
            'kat' => 'required',
            'code' => 'required|unique:products',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Naslov je obavezan.',
            'publish_at.required' => 'Datum objave proizvoda je obavezan.',
            'short.required' => 'Kratak opis je obavezan.',
            'price_small.required' => 'Maloprodajna cena je obavezna.',
            'price_small.numeric' => 'Maloprodajna cena mora biti broj.',
            'price_small.min' => 'Maloprodajna cena mora biti pozitivan broj.',
            'img.image' => 'Uploudovani fajl mora biti slika.',
            'img.mimes' => 'Slika mora biti u jpeg, jpg ili png formatu.',
            'kat.required' => 'Jedna kategorija je obavezna.',
            'code.required' => 'Šifra je obavezna.',
            'code.unique' => 'Šifra nije jedinstvena.'
        ];
    }
}
