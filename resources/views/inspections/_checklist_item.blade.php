<li class="list-group-item d-flex justify-content-between align-items-center {{ $item->concluido ? 'text-decoration-line-through text-muted' : '' }}" data-item-id="{{ $item->id }}">
    <div> <input class="form-check-input me-2" type="checkbox" id="check-{{ $item->id }}" data-item-id="{{ $item->id }}" {{ $item->concluido ? 'checked' : '' }} {{ $disabled ? 'disabled' : '' }}>

        <label class="form-check-label" for="check-{{ $item->id }}">
            {{ $item->descricao }}
        </label>

        @if($item->obrigatorio)
        <span class="badge bg-danger ms-2">Obrigatório</span>
        @endif
    </div>

    @if(!$disabled)
    <button class="btn btn-sm btn-outline-danger btn-remove-item" data-item-id="{{ $item->id }}">X</button> @endif
</li>