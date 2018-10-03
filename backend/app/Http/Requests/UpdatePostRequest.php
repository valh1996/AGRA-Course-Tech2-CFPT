<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'message' => 'required|string',
            'current_cover_images.*' => 'numeric',
            'new_cover_images.*' => 'image|max:3072',
        ];
    }

    public function messages()
    {
        return [
            'message.required' => 'Veuillez saisir un texte pour votre annonce.',
            'message.string' => 'Veuillez saisir une chaîne de caractère',
            'new_cover_images.*.image' => 'Vous ne pouvez uploader que des images.',
            'current_cover_images.*' => 'Une erreur est survenue lors de la validation'
        ];
    }
}
