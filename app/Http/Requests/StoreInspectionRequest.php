<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInspectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Não há autentificação de usuários, então deixo true
        // apenas para ambiente de testes.
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
            'titulo' => 'required|string|max:255',
            'cep' => 'required|string|max:9', // 8 Dígitos + o traço que será adicionado mais tarde.
            'logradouro' => 'nullable|string|max:255',
            'numero' => 'required|string|max:50',
            'bairro' => 'nullable|string|max:100',
            'cidade' => 'required|string|max:100',
            'uf' => 'nullable|string|max:2',
            'data_prevista' => 'required|date',
        ];
    }
}
