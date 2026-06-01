<?php

namespace App\Enums;

enum Ambiente: string
{
    case Producao      = 'Producao';
    case Homologacao   = 'Homologacao';
    case Desenvolvimento = 'Desenvolvimento';

    public function label(): string
    {
        return match($this) {
            Ambiente::Producao      => 'Produção',
            Ambiente::Homologacao   => 'Homologação',
            Ambiente::Desenvolvimento => 'Desenvolvimento',
        };
    }
}
