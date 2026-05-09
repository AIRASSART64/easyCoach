<?php


namespace App\Enum;

enum TypeStockManagment: string
{
    case ENTRANCE = 'Entrée (Achat/Don)';
    case EXIT = 'Sortie (Consommation)';
    case LOAN = 'Prêt au joueur';
    case RETURN = 'Retour de prêt';
    case LOSS = 'Perte / Vol';
    case DAMAGE = 'Casse / Dégradation';
    case ADJUSTMENT = 'Ajustement Inventaire';

    
    public function getSign(): int
    {
        return match($this) {
            self::ENTRANCE, self::RETURN => 1,  
            self::EXIT, self::LOAN, self::LOSS, self::DAMAGE => -1, 
            self::ADJUSTMENT => 1, 
        };
    }

    public function getColor(): string
    {
        return $this->getSign() > 0 ? 'text-green-600' : 'text-red-600';
    }
}