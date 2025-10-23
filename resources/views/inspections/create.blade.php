@extends('layout.app')

@section('content')
<h2>Nova Inspeção</h2>

<div class="card">
    <div class="card-body">
        <form action="{{ route('inspections.store') }}" method="POST">
            @csrf
            @include('inspections._form')

            <div class="mt-3">
                <a href="{{ route('inspections.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar e Adicionar Checklist</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@include('inspections._form-js')
@endpush