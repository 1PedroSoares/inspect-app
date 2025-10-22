<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    use HasFactory;

    /**
     * Campos que podem ser preenchidos rapidamente e com seeders.
     *
     * @var array
     */
    protected $fillable = [
        'inspection_id',
        'descricao',
        'obrigatorio',
        'concluido',
    ];

    /**
     * Moldando os atributos com Casts para o tipo boolean.
     *
     * @var array
     */
    protected $casts = [
        'obrigatorio' => 'boolean',
        'concluido' => 'boolean',
    ];

    /**
     * Defino o relacionamento: 1/1. Um Item de Checklist pertence a uma Inspeção.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }
}
