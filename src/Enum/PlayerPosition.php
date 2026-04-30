<?php

namespace App\Enum;

enum PlayerPosition: string
{
    case GARDIEN = 'Gardien';
    case DEFENSEUR = 'Défenseur';
    case MILIEU = 'Milieu';
    case ATTAQUANT = 'Attaquant';
}