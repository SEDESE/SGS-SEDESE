<?php

namespace App\Models;

use App\Enums\FamiliaOS;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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

    public function aplicacoes()
    {
        // Relacionamento será completado na sprint de Aplicações (RF-05)
        // return $this->hasMany(Aplicacao::class);
    }
}
