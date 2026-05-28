<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    public $timestamps = false; // só tem created_at

    const CREATED_AT = 'created_at';

    protected $fillable = ['user_id', 'evento', 'ip'];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(['name' => 'Usuário removido']);
    }
}