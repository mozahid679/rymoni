<?php

namespace App\Services;

use App\Models\MeterReading;
use App\Models\Bill;

class ElectricityCalculationService
{
    public function calculate(string $month): void
    {
        $readings = MeterReading::with('meter.property.units')
            ->where('month', $month)
            ->get();

        foreach ($readings as $reading) {
            $this->distributeBill($reading);
        }
    }

    protected function distributeBill(MeterReading $reading): void
    {
        $usage = $reading->current_unit - $reading->previous_unit;
        $totalCost = $usage * $reading->per_unit_price;

        $shops = $reading->meter->property
            ->units()
            ->where('type', 'shop')
            ->where('has_electricity', true)
            ->get();

        if ($shops->isEmpty()) return;

        $perShopCost = $totalCost / $shops->count();

        foreach ($shops as $shop) {
            $bill = Bill::where('unit_id', $shop->id)
                ->where('month', $reading->month)
                ->first();

            if (!$bill) continue;

            $bill->update([
                'electricity_amount' => round($perShopCost, 2),
                'total_amount'       => $bill->rent_amount + round($perShopCost, 2),
            ]);
        }
    }
}
