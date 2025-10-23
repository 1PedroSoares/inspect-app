<?php

namespace App\Http\Controllers\Api;

use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Services\ViaCepService;
use Throwable; // Uso Throwable ao invéz do Exception para pegar todos os erros e exceções

class CepController extends Controller
{
    protected $viaCepService;

    /**
     * Injeto a Service ViaCepService via contructor para facilitar a
     * implementação de testes, manutenção e escala.
     */
    public function __construct(ViaCepService $viaCepService)
    {
        $this->viaCepService = $viaCepService;
    }

    /**
     * Consulto o endereço de acordo com o Cep.
     * * Rota: GET /api/cep/{cep}
     * * @param string $cep O CEP que vai ser consultado.
     * @return JsonResponse
     */
    public function consultarEnderecoPeloCep(string $cep): JsonResponse
    {
        try {
            $endereco = $this->viaCepService->consultarCep($cep);
            return response()->json($endereco, 200);

            // Captura um erro de validação (400).
        } catch (ValidationException $e) {
            // Pega a *primeira* mensagem de erro para exibir no frontend.
            $primeiraMensagem = data_get(array_values($e->errors()), '0.0', 'Erro de validação.');

            return response()->json(['error' => $primeiraMensagem], 400);

            // Captura *qualquer* outro erro (500) usando Throwable.
        } catch (Throwable $e) {
            Log::error("Erro ao consultar ViaCEP: " . $e->getMessage());

            // CORREÇÃO 3: Corrigido o typo de "Flaha" para "Falha"
            return response()->json(['error' => "Falha na requisição ao consultar o CEP"], 500);
        }
    }
}
