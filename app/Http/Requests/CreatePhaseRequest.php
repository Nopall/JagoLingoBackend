<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePhaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'course_id' => 'required',
            'package_id' => 'nullable',
            'phase_title' => 'nullable|required|string|max:255', // Title hanya wajib jika type = audio_image
            'description' => 'nullable|required|string|max:255',
        ];
    }
}

