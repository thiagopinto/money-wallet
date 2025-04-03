<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999.99'],
            'payer_id' => ['required', 'integer', 'exists:users,id'],
            'payee_id' => ['required', 'integer', 'exists:users,id', 'different:payer'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'O valor é obrigatório',
            'amount.numeric' => 'O valor deve ser um número',
            'amount.min' => 'O valor mínimo é R$ 0,01',
            'amount.max' => 'O valor máximo é R$ 9.999.999,99',
            'payer_id.required' => 'O pagador é obrigatório',
            'payer_id.exists' => 'Pagador não encontrado',
            'payee_id.required' => 'O beneficiário é obrigatório',
            'payee_id.exists' => 'Beneficiário não encontrado',
            'payee_id.different' => 'Não é possível transferir para si mesmo',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Erro de validação',
            'errors' => $validator->errors()
        ], 422));
    }
}
