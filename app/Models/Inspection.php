<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inspection extends Model
{
    use SoftDeletes;

    /**
     * Campos que podem ser preenchidos repidamente.
     *
     * @var array
     */
    protected $fillable = [
        'titulo',
        'cep',
        'logradouro',
        'numero',
        'bairro',
        'cidade',
        'uf',
        'data_prevista',
        'status',
    ];

    /**
     * Moldando o atributo data_prevista com Casts para o tipo datetime.
     *
     * @var array
     */
    protected $casts = [
        'data_prevista' => 'datetime',
    ];

    /**
     * Definindo o relacionamento: 1/n. Uma Inspeção tem muitos Itens de Checklist.
     *
     * @return \HasMany
     */
    public function checklistItems(): HasMany
    {
        return $this->hasMany(ChecklistItem::class);
    }
}
