namespace App\Enums;

enum Ambiente: string
{
    case Producao = 'Producao';
    case Homologacao = 'Homologacao';
    case Desenvolvimento = 'Desenvolvimento';
}