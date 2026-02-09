<?php

namespace App\Services;

use App\Models\Tenancy;
use App\Models\Bill;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BillGenerationService
{
    public function generate(string $month): void
    {
        $start = Carbon::parse($month . '-01')->startOfMonth();
        $end   = Carbon::parse($month . '-01')->endOfMonth();

        $tenancies = Tenancy::where('start_date', '<=', $end)
            ->where(function ($q) use ($start) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', $start);
            })
            ->with(['tenant', 'unit'])
            ->get();

        foreach ($tenancies as $tenancy) {
            $this->createBill($tenancy, $month);
        }
    }

    protected function createBill(Tenancy $tenancy, string $month): void
    {
        // prevent duplicate bills
        if (
            Bill::where('tenant_id', $tenancy->tenant_id)
            ->where('unit_id', $tenancy->unit_id)
            ->where('month', $month)
            ->exists()
        ) {
            return;
        }

        $rent = $tenancy->unit->monthly_rent;

        Bill::create([
            'invoice_no'        => 'INV-' . strtoupper(Str::random(8)),
            'tenant_id'         => $tenancy->tenant_id,
            'unit_id'           => $tenancy->unit_id,
            'month'             => $month,
            'rent_amount'       => $rent,
            'electricity_amount' => 0, // Step 2 will update this
            'total_amount'      => $rent,
            'issue_date'        => now(),
            'due_date'          => now()->addDays(10),
            'status'            => 'unpaid',
        ]);
    }
}
