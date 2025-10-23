@extends('layout.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Gerenciar Inspeção: {{ $inspection->titulo }}</h2>
    <div>
        @if($inspection->status == 'Pendente')
        <form action="{{ route('inspections.concluir', $inspection->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja realmente concluir esta inspeção?')">
            @csrf
            <button type="submit" class="btn btn-success">Concluir Inspeção</button>
        </form>
        @else
        <span class="btn btn-success disabled">Inspeção Concluída</span>
        @endif
    </div>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card mb-4">
    <div class="card-header">Dados da Inspeção</div>
    <div class="card-body">
        <form action="{{ route('inspections.update', $inspection->id) }}" method="POST">
            @csrf
            @method('PUT')

            @include('inspections._form', ['inspection' => $inspection])

            <div class="mt-3">
                <a href="{{ route('inspections.index') }}" class="btn btn-secondary">Voltar</a>
                <button type="submit" class="btn btn-primary" {{ $inspection->status == 'Concluida' ? 'disabled' : '' }}>Atualizar Dados</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Checklist</div>
    <div class="card-body">

        @if($inspection->status == 'Pendente')
        <form id="form-add-item" class="row g-3 mb-4" data-inspection-id="{{ $inspection->id }}">
            <div class="col-md-7">
                <input type="text" id="item-descricao" class="form-control" placeholder="Descrição do novo item" required>
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="item-obrigatorio">
                    <label class="form-check-label" for="item-obrigatorio">
                        Obrigatório?
                    </label>
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-info w-100">Adicionar</button>
            </div>
        </form>
        @endif

        <ul class="list-group" id="checklist-container">
            @forelse($inspection->checklistItems as $item)
            @include('inspections._checklist_item', ['item' => $item, 'disabled' => $inspection->status == 'Concluida'])
            @empty
            <li id="no-items-message" class="list-group-item text-center text-muted">Nenhum item no checklist.</li>
            @endforelse
        </ul>

        <div class="mt-4 text-end">
            @if($inspection->status == 'Pendente')
            <form action="{{ route('inspections.concluir', $inspection->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja realmente concluir esta inspeção?')">
                @csrf
                <button type="submit" class="btn btn-success">Concluir Inspeção</button>
            </form>
            @else
            <span class="btn btn-success disabled">Inspeção Concluída</span>
            @endif
        </div>
        </div>
</div>
@endsection

@push('scripts')
@include('inspections._form-js')

<script>
    $(document).ready(function() {
        // Configuração global do AJAX para incluir o Token CSRF em todo o percurso do código.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const inspectionId = $('#form-add-item').data('inspection-id');
        const checklistContainer = $('#checklist-container');
        const noItemsMessage = $('#no-items-message');
        const isConcluida = {{ $inspection->status == 'Concluida' ? 1 : 0 }};

        // Adiciono o Item (AJAX)
        $('#form-add-item').submit(function(e) {
            e.preventDefault();

            const descricao = $('#item-descricao').val();
            const obrigatorio = $('#item-obrigatorio').is(':checked');

            $.ajax({
                // Rota: checklist.store
                url: `/inspections/${inspectionId}/checklist`,
                type: 'POST',
                data: {
                    descricao: descricao,
                    obrigatorio: obrigatorio // Envia true/false
                },
                success: function(newItem) {
                    // Limpa o formulário.
                    $('#item-descricao').val('');
                    $('#item-obrigatorio').prop('checked', false);

                    // Remove a mensagem "Nenhum item".
                    if (noItemsMessage) noItemsMessage.remove();

                    // Adiciona o novo item na lista (usando um template teste).
                    // Monto o HTML aqui para manter o código mais simples e adequar ao KISS.
                    const newItemHtml = `
                            <li class="list-group-item d-flex justify-content-between align-items-center" data-item-id="${newItem.id}">
                                <div>
                                    <input class="form-check-input me-2" type="checkbox" value="" 
                                           id="check-${newItem.id}" data-item-id="${newItem.id}">
                                    <label class="form-check-label" for="check-${newItem.id}">
                                        ${newItem.descricao}
                                    </label>
                                    ${newItem.obrigatorio ? '<span class="badge bg-danger ms-2">Obrigatório</span>' : ''}
                                </div>
                                <button class="btn btn-sm btn-outline-danger btn-remove-item" data-item-id="${newItem.id}">X</button>
                            </li>
                        `;
                    checklistContainer.append(newItemHtml);
                },
                error: function(xhr) {
                    // Tratamento de erro
                    let errorMsg = 'Ocorreu um erro desconhecido.';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.error) {
                            errorMsg = xhr.responseJSON.error;
                        } else if (xhr.responseJSON.errors) {
                            const firstErrorKey = Object.keys(xhr.responseJSON.errors)[0];
                            errorMsg = xhr.responseJSON.errors[firstErrorKey][0];
                        } else if (xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                    }
                    alert('Erro ao adicionar item: ' + errorMsg);
                }
            });
        });

        // Marcar/Desmarcar Item (AJAX) - usando delegação de evento
        checklistContainer.on('change', '.form-check-input', function() {
            const checkbox = $(this);
            const itemId = checkbox.data('item-id');
            const concluido = checkbox.is(':checked');

            $.ajax({
                url: `/checklist-items/${itemId}`, // Rota: checklist.update
                type: 'PUT',
                data: {
                    concluido: concluido
                },
                success: function(updatedItem) {
                    // Apenas confirma visualmente
                    checkbox.closest('li').toggleClass('text-decoration-line-through text-muted', updatedItem.concluido);
                },
                error: function(xhr) {
                    // Tratamento de erro
                    let errorMsg = 'Ocorreu um erro desconhecido.';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.error) {
                            errorMsg = xhr.responseJSON.error;
                        } else if (xhr.responseJSON.errors) {
                            const firstErrorKey = Object.keys(xhr.responseJSON.errors)[0];
                            errorMsg = xhr.responseJSON.errors[firstErrorKey][0];
                        } else if (xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                    }
                    alert('Erro: ' + errorMsg);
                    
                    // Desfaz a marcação se deu erro
                    checkbox.prop('checked', !concluido);
                }
            });
        });

        // Remove Item (AJAX)
        checklistContainer.on('click', '.btn-remove-item', function() {
            if (isConcluida) {
                alert('Não é possível remover itens de uma inspeção concluída.');
                return;
            }

            if (!confirm('Deseja realmente remover este item?')) {
                return;
            }

            const button = $(this);
            const itemId = button.data('item-id');
            const itemLi = button.closest('li');

            $.ajax({
                url: `/checklist-items/${itemId}`, // Rota: checklist.destroy
                type: 'DELETE',
                success: function() {
                    // Remove o item da tela
                    itemLi.fadeOut(300, function() {
                        $(this).remove();
                        // Se for o último item, mostra a msg
                        if (checklistContainer.children().length === 0) {
                            checklistContainer.append('<li id="no-items-message" class="list-group-item text-center text-muted">Nenhum item no checklist.</li>');
                        }
                    });
                },
                error: function(xhr) {
                    let errorMsg = 'Ocorreu um erro desconhecido.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    alert('Erro: ' + errorMsg);
                }
            });
        });
    });
</script>
@endpush