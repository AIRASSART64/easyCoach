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
}