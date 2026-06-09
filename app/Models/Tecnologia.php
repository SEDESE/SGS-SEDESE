<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tecnologia extends Model
{
    protected $table = 'tecnologias';

    protected $fillable = ['nome'];

    public function aplicacoes(): BelongsToMany
    {
        return $this->belongsToMany(Aplicacao::class, 'aplicacao_tecnologia');
    }
}
