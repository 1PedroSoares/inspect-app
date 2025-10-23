<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInspectionRequest;
use Illuminate\Contracts\View\View;
use App\Services\InspecaoService;
use Illuminate\Http\Request;
use App\Models\Inspection;

class InspectionController extends Controller
{

    protected $inspecaoService;

    /**
     * Injeto a Service InspecaoService via contructor para facilitar a
     * implementação de testes, manutenção e escala.
     */
    public function __construct(InspecaoService $inspecaoService)
    {
        $this->inspecaoService = $inspecaoService;
    }

    /**
     * Lista todas as Inspeções compactadas em um array associativo.
     * Rota: GET /inspections
     * 
     * @return View
     */
    public function index()
    {
        $inspections = Inspection::orderBy('data_prevista', 'desc')->paginate(10);
        return view('inspections.index', compact('inspections'));
    }

    /**
     * Mostra o formulário de criação de uma nova Inspeção.
     * Rota: GET /inspections/create
     * 
     * @return View
     */
    public function create()
    {
        return view('inspections.create');
    }

    /**
     * Salva uma nova inspeção.
     * Rota: POST /inspections
     *
     * @param StoreInspectionRequest $request A requisição validada.
     * @return RedirectResponse
     */
    public function store(StoreInspectionRequest $request)
    {
        $inspection = Inspection::create($request->validated());

        // Redireciona para a tela de EDIÇÃO para adicionar os itens do checklist
        // separando a criação da inspeção da criação dos itens e mantendo mais simples KISS.
        return redirect()->route('inspections.edit', $inspection->id)
            ->with('success', 'Inspeção criada! Adicione os itens do checklist.');
    }

    /**
     * Mostra os detalhes de uma inspeção.
     * Rota: GET /inspections/{inspection}
     *
     * @param Inspection $inspection Model injetado automaticamente 
     * pelo Route Model do Laravel.
     * 
     * @return View
     */
    public function show(Inspection $inspection)
    {
        // Carrega todos os itens do checklist juntos
        $inspection->load('checklistItems');
        return view('inspections.show', compact('inspection'));
    }

    /**
     * Mostra o formulário de edição, onde o checklist será gerenciado.
     * Rota: GET /inspections/{inspection}/edit
     *
     * @param Inspection $inspection
     * @return View
     */
    public function edit(Inspection $inspection)
    {
        $inspection->load('checklistItems');
        return view('inspections.edit', compact('inspection'));
    }

    /**
     * Atualiza uma inspeção existente.
     * Rota: PUT/PATCH /inspections/{inspection}
     *
     * @param StoreInspectionRequest $request A requisição validada
     * @param Inspection $inspection
     * @return RedirectResponse
     */
    public function update(StoreInspectionRequest $request, Inspection $inspection)
    {
        $inspection->update($request->validated());

        return redirect()->route('inspections.edit', $inspection->id)
            ->with('success', 'Inspeção atualizada com sucesso.');
    }

    /**
     * Exclui uma inspeção.
     * Rota: DELETE /inspections/{inspection}
     *
     * @param Inspection $inspection
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Inspection $inspection)
    {
        $inspection->delete();
        return redirect()->route('inspections.index')
            ->with('success', 'Inspeção excluída com sucesso.');
    }

    /**
     * Tenta concluir a inspeção usando a regra de negócio do Serviço.
     * Rota: POST /inspections/{inspection}/concluir
     *
     * @param Inspection $inspection
     * @return RedirectResponse
     */
    public function concluir(Inspection $inspection)
    {
        try {
            $this->inspecaoService->concluirInspecao($inspection);
            return redirect()->route('inspections.index')
                ->with('success', 'Inspeção concluída com sucesso!');
        } catch (\Exception $e) {
            // Retorna para a tela de edição com o erro da regra de negócio
            return redirect()->route('inspections.edit', $inspection->id)
                ->with('error', $e->getMessage());
        }
    }
}
