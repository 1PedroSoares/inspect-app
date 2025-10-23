<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Inspection;

class InspectionConclusionTest extends TestCase
{
    use RefreshDatabase; // Reseta o banco a cada teste

    /**
     * Teste: Não deve concluir se houver itens obrigatórios pendentes.
     * @test
     */
    public function it_should_not_conclude_if_required_items_are_pending(): void
    {

        // 1. Arrange (Cria o cenário).
        $inspection = Inspection::factory()->create();

        // Item obrigatório PENDENTE.
        $inspection->checklistItems()->create([
            'descricao' => 'Item 1',
            'obrigatorio' => true,
            'concluido' => false
        ]);
        // Item obrigatório CONCLUÍDO.
        $inspection->checklistItems()->create([
            'descricao' => 'Item 2',
            'obrigatorio' => true,
            'concluido' => true
        ]);

        // 2. Act (Chama a rota de conclusão).
        $response = $this->post(route('inspections.concluir', $inspection));

        // 3. Assert (Verifica o resultado).
        // Deve redirecionar de volta (para 'edit') com um erro.
        $response->assertRedirect(route('inspections.edit', $inspection));
        $response->assertSessionHas('error', 'Não é possível concluir. Existem 1 item(ns) obrigatório(s) pendente(s).');

        // Verifica se o status no banco NÃO mudou.
        $this->assertDatabaseHas('inspections', [
            'id' => $inspection->id,
            'status' => 'Pendente'
        ]);
    }

    /**
     * Teste: Deve concluir com sucesso se todos obrigatórios estiverem OK.
     * @test
     */
    public function it_should_conclude_if_all_required_items_are_done(): void
    {

        // 1. Arrange.
        $inspection = Inspection::factory()->create();

        // Item obrigatório CONCLUÍDO.
        $inspection->checklistItems()->create([
            'descricao' => 'Item 1',
            'obrigatorio' => true,
            'concluido' => true
        ]);
        // Item NÃO obrigatório PENDENTE (não deve impedir).
        $inspection->checklistItems()->create([
            'descricao' => 'Item 2',
            'obrigatorio' => false,
            'concluido' => false
        ]);

        // 2. Act.
        $response = $this->post(route('inspections.concluir', $inspection));

        // 3. Assert.
        // Deve redirecionar para 'index' com sucesso.
        $response->assertRedirect(route('inspections.index'));
        $response->assertSessionHas('success', 'Inspeção concluída com sucesso!');

        // Verifica se o status no banco MUDOU.
        $this->assertDatabaseHas('inspections', [
            'id' => $inspection->id,
            'status' => 'Concluida'
        ]);
    }
}
