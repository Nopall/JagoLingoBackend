<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePembiayaanRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'cp_1' => 'required|string',
            'cp_2' => 'nullable|string',
            'wa_1' => 'required|string',
            'wa_2' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png|max:2048',
        ];
    }
}
