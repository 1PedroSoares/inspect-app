@php
$data = $inspection ?? null;
$disabled = (isset($inspection) && $inspection->status == 'Concluida') ? 'disabled' : '';
@endphp

@if ($errors->any())
<div class="alert alert-danger">
    <strong>Ops!</strong> Verifique os erros abaixo:
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row g-3">
    <div class="col-md-12">
        <label for="titulo" class="form-label">Título da Inspeção</label>
        <input type="text" class="form-control" id="titulo" name="titulo"
            value="{{ old('titulo', $data->titulo ?? '') }}" required {{ $disabled }}>
    </div>

    <div class="col-md-6">
        <label for="data_prevista" class="form-label">Data Prevista</label>
        <input type="datetime-local" class="form-control" id="data_prevista" name="data_prevista"
            value="{{ old('data_prevista', $data ? $data->data_prevista->format('Y-m-d\TH:i') : '') }}" required {{ $disabled }}>
    </div>

    <div class="col-md-4">
        <label for="cep" class="form-label">CEP</label>
        <input type="text" class="form-control" id="cep" name="cep"
            value="{{ old('cep', $data->cep ?? '') }}" required {{ $disabled }}>
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button type="button" class="btn btn-secondary w-100" id="btn-buscar-cep" {{ $disabled }}>Buscar CEP</button>
    </div>
    <div id="cep-error" class="text-danger small mt-1" style="display: none;"></div>

    <div class="col-md-8">
        <label for="logradouro" class="form-label">Logradouro</label>
        <input type="text" class="form-control" id="logradouro" name="logradouro"
            value="{{ old('logradouro', $data->logradouro ?? '') }}" required {{ $disabled }}>
    </div>
    <div class="col-md-4">
        <label for="numero" class="form-label">Número</label>
        <input type="text" class="form-control" id="numero" name="numero"
            value="{{ old('numero', $data->numero ?? '') }}" required {{ $disabled }}>
    </div>

    <div class="col-md-4">
        <label for="bairro" class="form-label">Bairro</label>
        <input type="text" class="form-control" id="bairro" name="bairro"
            value="{{ old('bairro', $data->bairro ?? '') }}" {{ $disabled }}>
    </div>
    <div class="col-md-6">
        <label for="cidade" class="form-label">Cidade</label>
        <input type="text" class="form-control" id="cidade" name="cidade"
            value="{{ old('cidade', $data->cidade ?? '') }}" required {{ $disabled }}>
    </div>
    <div class="col-md-2">
        <label for="uf" class="form-label">UF</label>
        <input type="text" class="form-control" id="uf" name="uf"
            value="{{ old('uf', $data->uf ?? '') }}" {{ $disabled }}>
    </div>
</div>