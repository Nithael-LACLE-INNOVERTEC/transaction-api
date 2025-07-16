<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
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
            'category_id' => [
                'required',
                Rule::exists('categories','id')->where('user_id', auth()->id()),
            ],
            'transaction_date' => ['required', 'date'],
            'amount' => ['required'],
            'description' => ['string', 'nullable'],
        ];
    }

    /**
     * Summary of messages
     * @return array
     */
    public function messages(): array
    {
        return [
            'category_id.required' => "La :attribute est manquante",
            'transaction_date.required' => "La :attribute est requise",
            'amount.required' => "Le :attribute est requis",

            'category_id.exists' => "La :attribute choisie n'existe pas",
            'transaction_date.date' => "la :attribute saisie est incorrecte (ex:" . now()->format('Y-m-d') . ")",
        ];
    }


    /**
     * Summary of attributes
     * @return array
     */
    public function attributes(): array
    {
        return [
            'category_id' => "catégorie",
            'transaction_date' => "date de la transaction",
            'amount' => "montant de la transaction",
            'description' => "description ou note",
        ];
    }
}
