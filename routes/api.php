<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CepController;

// Rota pública para consulta de CEP
Route::get('/cep/{cep}', [CepController::class, 'consultarEnderecoPeloCep']);
