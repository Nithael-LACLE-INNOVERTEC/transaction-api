<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => "Le :attribute est obligatoire",
            'name.min' => "Saisissez au moins 3 caractères dans le champ :attribute",
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => "nom de la catégorie"
        ];
    }
}
