<?php

namespace App\Enums;

enum Role: string
{
    case Administrador = 'administrador';
    case Operador = 'operador';

    public function label(): string
    {
        return match($this) {
            Role::Administrador => 'Administrador',
            Role::Operador => 'Operador',
        };
    }
}