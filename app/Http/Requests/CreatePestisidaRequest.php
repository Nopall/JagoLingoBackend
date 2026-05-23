<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePestisidaRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'seller' => 'required|string',
            'contact_no' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,png|max:2048',
        ];
    }
}
