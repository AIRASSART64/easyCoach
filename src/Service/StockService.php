<?


namespace App\Service;

use App\Entity\Equipment;

class StockService
{
    
    public function calculateCurrentStock(Equipment $equipment): int
    {
        $total = $equipment->getTotalQuantity() ?? 0;

        foreach ($equipment->getStocks() as $stock) {
            $total += ($stock->getQuantity() * $stock->getType()->getSign());
        }

        return $total;
    }

  
    public function isStockLow(Equipment $equipment): bool
    {
        $current = $this->calculateCurrentStock($equipment);
        $min = $equipment->getAlertLevel() ?? 0;

        return $current <= $min;
    }
}