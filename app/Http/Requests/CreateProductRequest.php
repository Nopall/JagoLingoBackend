<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required',
            'weight' => 'required',
            'uom' => 'required',
            'contact' => 'required',
            'commodity_id'=> 'required'
        ];
    }
}
