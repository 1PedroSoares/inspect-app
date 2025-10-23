<?php

namespace App\Services;

use App\Models\Inspection;
use Exception;

class InspecaoService
{
    /**
     * Conclui uma inspeção ou retorna um erro caso 
     * não atenda as regras de negócio.
     *
     * Regra: Só pode concluir a Inspeção se todos os itens
     * obrigatórios estiverem marcados como 'concluido'.
     *
     * @param Inspection $inspection A inspeção a ser concluída.
     * @return bool True se for concluída, gera uma exceção caso contrário.
     * @throws \Exception Se a regra não for atendida.
     */
    public function concluirInspecao(Inspection $inspection): bool
    {

        if ($inspection->status === 'Concluida') {
            throw new Exception('Esta inspeção já está concluída.');
        }

        // Conto quantos itens que são obrigatórios,
        // mas ainda não foram concluídos.
        $itensPendentes = $inspection->checklistItems()
            ->where('obrigatorio', true)
            ->where('concluido', false)
            ->count();

        if ($itensPendentes > 0) {
            throw new Exception("Não foi possível concluir. Os intens, $itensPendentes obrigatórios estão pendentes.");
        }

        // Nesse ponto a Inspeção pode ser concluída se não tiver retornado
        // nenhum erro.
        $inspection->status = 'Concluida';
        return $inspection->save();
    }
}
