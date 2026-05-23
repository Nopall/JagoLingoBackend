<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductImageRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png|max:2048',
        ];
    }
}
