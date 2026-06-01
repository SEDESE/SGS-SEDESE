<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alteracao extends Model
{
    protected $table = 'alteracoes';

    protected $fillable = [
        'user_id',
        'aplicacao_id',
        'descricao',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function aplicacao(): BelongsTo
    {
        return $this->belongsTo(Aplicacao::class, 'aplicacao_id');
    }
}
