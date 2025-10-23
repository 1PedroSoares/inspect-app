<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class ViaCepService
{
    /**
     * Consulta o CEP na API do ViaCEP e retorna um array com os dados do endereço.
     *
     * @param string $cep O CEP a ser consultado - somente números sem espaço.
     * @return array Os dados do endereço.
     * @throws \ValidationException Se o CEP for inválido ou não encontrado.
     */
    public function consultar(string $cep): array
    {

        // Limpa o CEP (remove traços/pontos).
        // Uso o preg_replace ao invés do string_replace para poder usar regex no filtro do cep.
        $cepLimpo = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cepLimpo) !== 8) {
            throw ValidationException::withMessages([
                'cep' => 'O CEP deve conter 8 dígitos.'
            ]);
        }

        // Armazeno a resposta da API em json atravéz do método GET.
        $response = Http::get("https://viacep.com.br/ws/{$cepLimpo}/json/");

        // Trato a falha e retorno com uma mensagem de fácil interpretação.
        if ($response->failed() || $response->json('erro') === true) {
            throw ValidationException::withMessages([
                'cep' => 'CEP não encontrado ou inválido.'
            ]);
        }

        $data = $response->json();

        // Retorno apenas os campos úteis para a Inspeção.
        return [
            'cep' => $data['cep'],
            'logradouro' => $data['logradouro'],
            'bairro' => $data['bairro'],
            'cidade' => $data['localidade'],
            'uf' => $data['uf'],
        ];
    }
}
