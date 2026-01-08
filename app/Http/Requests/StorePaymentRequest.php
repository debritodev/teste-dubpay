<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'amount' => ['required', 'integer', 'min:100'],
            
            'provider' => ['nullable', 'string', 'exists:payment_providers,slug'],
            
            'card_number' => ['nullable', 'string'],
            'cvv' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min' => 'O valor mínimo para transação é de R$ 1,00.',
            'provider.exists' => 'O gateway de pagamento selecionado é inválido ou não existe.',
        ];
    }
}