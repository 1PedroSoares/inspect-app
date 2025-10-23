<?php

use App\Http\Controllers\ChecklistItemController;
use App\Http\Controllers\InspectionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rota principal
Route::get('/', [InspectionController::class, 'index'])->name('home');

// CRUD de Inspeções
Route::resource('inspections', InspectionController::class);

// Rota de Negócio: Concluir Inspeção
Route::post('inspections/{inspection}/concluir', [InspectionController::class, 'concluir'])->name('inspections.concluir');

// Rotas do Checklist (via AJAX, mas dentro do 'web' para usar sessões/CSRF)
Route::post('inspections/{inspection}/checklist', [ChecklistItemController::class, 'store'])->name('checklist.store');
Route::put('checklist-items/{checklistItem}', [ChecklistItemController::class, 'update'])->name('checklist.update');
Route::delete('checklist-items/{checklistItem}', [ChecklistItemController::class, 'destroy'])->name('checklist.destroy');
