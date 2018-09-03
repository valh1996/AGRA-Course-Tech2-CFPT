<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'images' => 'image'
        ];
    }

    public function messages()
    {
        return [
            'message.required' => 'Veuillez saisir un texte pour votre annonce.',
            'message.string' => 'Veuillez saisir une chaîne de caractère',
            'images.image' => 'Vous ne pouvez uploader que des images.',
        ];
    }
}
