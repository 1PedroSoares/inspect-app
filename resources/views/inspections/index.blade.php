@extends('layout.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Inspeções Agendadas</h2>
    <a href="{{ route('inspections.create') }}" class="btn btn-primary">Nova Inspeção</a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Endereço</th>
                    <th>Data Prevista</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inspections as $inspection)
                <tr>
                    <td>{{ $inspection->titulo }}</td>
                    <td>{{ $inspection->logradouro }}, {{ $inspection->numero }} - {{ $inspection->cidade }}</td>
                    <td>{{ $inspection->data_prevista->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="badge {{ $inspection->status == 'Pendente' ? 'bg-warning' : 'bg-success' }}">
                            {{ $inspection->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('inspections.edit', $inspection->id) }}" class="btn btn-sm btn-warning">Gerenciar</a>
                        <form action="{{ route('inspections.destroy', $inspection->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Nenhuma inspeção encontrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $inspections->links() }}
        </div>
    </div>
</div>
@endsection