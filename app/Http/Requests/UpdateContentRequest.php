<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'cover' => 'nullable|image|mimes:png,jpg,jpeg',
            'price' => 'nullable|string',
            'file' => 'nullable|mimes:pdf,doc,docx',
            'type_id' => 'nullable|integer',
            'genre_id' => 'nullable|integer',
            'editor_id' => 'nullable|integer',
            'author_id' => 'nullable|integer',
            'publisher_id' => 'nullable|integer',
            'status_id' => 'nullable|integer',
        ];
    }
}
