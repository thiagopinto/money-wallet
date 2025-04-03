<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => ['required', 'numeric', 'min:0.01', 'max:9999999.99'],
            'payer' => ['required', 'integer', 'exists:users,id'],
            'payee' => ['required', 'integer', 'exists:users,id', 'different:payer'],
        ];
    }

    public function messages(): array
    {
        return [
            'value.required' => 'O valor é obrigatório',
            'value.numeric' => 'O valor deve ser um número',
            'value.min' => 'O valor mínimo é R$ 0,01',
            'value.max' => 'O valor máximo é R$ 9.999.999,99',
            'payer.required' => 'O pagador é obrigatório',
            'payer.exists' => 'Pagador não encontrado',
            'payee.required' => 'O beneficiário é obrigatório',
            'payee.exists' => 'Beneficiário não encontrado',
            'payee.different' => 'Não é possível transferir para si mesmo',
        ];
    }
}