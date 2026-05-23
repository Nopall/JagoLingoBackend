<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePhaseLessonRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phase_id' => 'required',
            'type' => 'required|in:audio,audio_image',
            'image' => 'nullable|required_if:type,audio_image|image|mimes:jpeg,png|max:2048', // Gambar hanya wajib jika type = audio_image
            'audio' => 'nullable|required_if:type,audio,audio_image|mimes:mp3,wav,ogg|max:5120', // Audio wajib jika type = audio atau audio_image
            'title' => 'nullable|required|string|max:255', // Title hanya wajib jika type = audio_image
        ];
    }
}

