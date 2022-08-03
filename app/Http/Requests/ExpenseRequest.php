<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
}
