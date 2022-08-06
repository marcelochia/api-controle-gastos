<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ExpenseRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'descricao' => 'required',
            'valor' => ['required', 'numeric'],
            'data' => ['required', 'date']
        ];
    }

    public function messages()
    {
        return [
            'descricao.required' => 'A descrição é obrigatória',
            'valor.required' => 'O valor é obrigatório',
            'valor.numeric' => 'O valor deve ser numérico',
            'data.required' => 'A data é obrigatória',
            'data.date' => 'A data está em formato inválido'
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        $response = new Response(['error' => $validator->errors()->all()], 422);
        throw new ValidationException($validator, $response);
    }
}
