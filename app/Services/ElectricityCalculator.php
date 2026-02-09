<?php

namespace App\Services;

use App\Models\MeterReading;
use App\Models\Tenancy;

class ElectricityCalculator
{
   public function calculateForMonth(string $month)
   {
      $reading = MeterReading::where('month', $month)->first();

      if (!$reading) {
         throw new \Exception('Meter reading not found for this month');
      }

      $totalUnits = $reading->current_unit - $reading->previous_unit;
      $totalCost  = $totalUnits * $reading->per_unit_price;

      // Active shop tenancies only
      $shopTenancies = Tenancy::whereNull('end_date')
         ->whereHas('unit', function ($q) {
            $q->where('type', 'shop')
               ->where('has_electricity', true);
         })
         ->with('unit')
         ->get();

      $shopCount = $shopTenancies->count();

      if ($shopCount === 0) {
         return [];
      }

      $perShopCost = round($totalCost / $shopCount, 2);

      return [
         'total_units' => $totalUnits,
         'total_cost' => $totalCost,
         'per_shop_cost' => $perShopCost,
         'shops' => $shopTenancies,
      ];
   }
}
