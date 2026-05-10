<?


namespace App\Service;

use App\Entity\Training;

class TrainingService
{
    public function getDurationFormatted(Training $training): ?string
    {
        if (!$training->getStartTime() || !$training->getEndTime()) {
            return null;
        }

        $interval = $training->getStartTime()->diff($training->getEndTime());
        
        $parts = [];
        if ($interval->h > 0) $parts[] = $interval->h . 'h';
        if ($interval->i > 0) $parts[] = $interval->i . 'min';

        return empty($parts) ? null : implode('', $parts);
    }
}