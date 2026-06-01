<?php

namespace App\Models;

use App\Enums\FamiliaOS;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SistemaOperacional extends Model
{
    protected $table = 'sistemas_operacionais';

    protected $fillable = [
        'nome',
        'familia',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'familia' => FamiliaOS::class,
            'ativo'   => 'boolean',
        ];
    }

    public function scopeAtivos(Builder $query): Builder
    {
        return $query->where('ativo', true);
    }

    public function aplicacoes(): HasMany
    {
        return $this->hasMany(Aplicacao::class, 'so_id');
    }
}
