<?php

namespace App\Enum;

enum TrainingTheme: string
{
    case TECHNIQUE = 'Technique';
    case JEUX_REDUITS = 'Jeux réduits';
    case ECHAUFFEMENT = 'Echauffement';
    case PHISIQUE = 'Physique';
    case TACTIQUE = 'Tactique';
}