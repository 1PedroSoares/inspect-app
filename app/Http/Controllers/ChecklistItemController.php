<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;
use App\Models\Inspection;

class ChecklistItemController extends Controller
{
    /**
     * Adiciona um novo item de checklist a uma inspeção.
     * Rota: POST /inspections/{inspection}/checklist
     *
     * @param Request $request
     * @param Inspection $inspection
     * @return JsonResponse
     */
    public function store(Request $request, Inspection $inspection): JsonResponse
    {
        // Valida apenas a descrição.
        $data = $request->validate([
            'descricao' => 'required|string|max:255',
        ]);

        // Usa $request->boolean() para processar o campo 'obrigatorio'
        // e garantir que não vai enviar uma string "true" ou "false".
        $data['obrigatorio'] = $request->boolean('obrigatorio');

        $item = $inspection->checklistItems()->create($data);

        // Retorna o item criado e o ID para o Front em JS.
        return response()->json($item, 201);
    }

    /**
     * Atualiza um item (ex: marcar como concluído).
     * Rota: PUT /checklist-items/{checklistItem}
     *
     * @param Request $request
     * @param ChecklistItem $checklistItem 
     * @return JsonResponse
     */
    public function update(Request $request, ChecklistItem $checklistItem): JsonResponse
    {
        // Valida apenas o campo 'concluido'.
        $data = $request->validate([
            'concluido' => 'required|boolean',
        ]);

        // Impede o usuário de desmarcar um item se a inspeção já estiver concluída.
        if ($checklistItem->inspection->status === 'Concluida' && $data['concluido'] === false) {
            return response()->json(['error' => 'Não pode reabrir itens de uma inspeção concluída.'], 403);
        }

        $checklistItem->update($data);

        return response()->json($checklistItem);
    }

    /**
     * Remove um item do checklist.
     * Rota: DELETE /checklist-items/{checklistItem}
     *
     * @param ChecklistItem $checklistItem
     * @return JsonResponse
     */
    public function destroy(ChecklistItem $checklistItem): JsonResponse
    {
        if ($checklistItem->inspection->status === 'Concluida') {
            return response()->json(['error' => 'Não pode excluir itens de uma inspeção concluída.'], 403);
        }

        $checklistItem->delete();

        return response()->json(null, 204); // 204 Sem conteúdo.
    }
}
