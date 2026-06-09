<?php

namespace App\Models;

use App\Enums\Ambiente;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class Aplicacao extends Model
{
    use HasFactory;

    protected $table = 'aplicacoes';

    protected $fillable = [
        'so_id',
        'nome_aplicacao',
        'ip',
        'ambiente',
        'url',
        'usuario_os',
        'senha_os',
        'usuario_site',
        'senha_site',
        'database',
        'usuario_db',
        'senha_db',
        'caminho',
        'git',
        'empresa_desenvolvedor',
        'responsavel_diretor',
    ];

    protected function casts(): array
    {
        return [
            'ambiente' => Ambiente::class,
        ];
    }

    // ─── Relacionamentos ────────────────────────────────────────────────────

    public function sistemaOperacional(): BelongsTo
    {
        return $this->belongsTo(SistemaOperacional::class, 'so_id');
    }

    public function alteracoes(): HasMany
    {
        return $this->hasMany(Alteracao::class, 'aplicacao_id');
    }

    // ─── Descriptografia (exclusivo para Administradores) ───────────────────

    public function senhaOsDecryptada(): ?string
    {
        return $this->descriptografar($this->senha_os);
    }

    public function senhaSiteDecryptada(): ?string
    {
        return $this->descriptografar($this->senha_site);
    }

    public function senhaDbDecryptada(): ?string
    {
        return $this->descriptografar($this->senha_db);
    }

    private function descriptografar(?string $valor): ?string
    {
        if (!$valor) {
            return null;
        }

        try {
            return Crypt::decryptString($valor);
        } catch (\Exception) {
            return null;
        }
    }
}
