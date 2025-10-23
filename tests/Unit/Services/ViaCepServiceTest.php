<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ViaCepService;
use Illuminate\Support\Facades\Http; // Usaremos o Mock do HTTP
use Illuminate\Validation\ValidationException;

class ViaCepServiceTest extends TestCase
{
    /**
     * Teste: Deve retornar o endereço com sucesso.
     * @test
     */
    public function it_should_return_address_on_success(): void
    {
        // 1. Arrange (Mock da resposta da API)
        $mockResponse = [
            'cep' => '32415-454',
            'logradouro' => 'Campo de Futebol',
            'bairro' => 'Canaã',
            'localidade' => 'Ibirité',
            'uf' => 'MG',
        ];

        // Finge que a chamada HTTP para QUALQUER URL retorna o mock.
        Http::fake([
            'viacep.com.br/*' => Http::response($mockResponse, 200)
        ]);

        // 2. Act (Executa o serviço).
        $service = new ViaCepService();
        $result = $service->consultarCep('01001000');

        // 3. Assert (Verifica o resultado).
        $this->assertEquals('Campo de Futebol', $result['logradouro']);
        $this->assertEquals('Ibirité', $result['cidade']);
    }

    /**
     * Teste: Deve lançar exceção se o CEP não for encontrado.
     * @test
     */
    public function it_should_throw_exception_if_cep_not_found(): void
    {
        // 1. Arrange (Mock de erro do ViaCEP)
        Http::fake([
            'viacep.com.br/*' => Http::response(['erro' => true], 200)
        ]);

        // 2. Assert (Espera que uma exceção específica seja lançada)
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('CEP não encontrado ou inválido.');

        // 3. Act
        $service = new ViaCepService();
        $service->consultarCep('99999999');
    }
}
