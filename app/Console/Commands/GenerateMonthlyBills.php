<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenancy;
use App\Models\Bill;
use App\Services\ElectricityCalculator;
use Carbon\Carbon;
use Illuminate\Support\Str;

class GenerateMonthlyBills extends Command
{
    protected $signature = 'bills:generate {month?}';
    protected $description = 'Generate bills for all active tenancies for a given month';

    public function handle()
    {
        $month = $this->argument('month') ?? now()->format('Y-m');

        $this->info("Generating bills for {$month}...");

        $tenancies = Tenancy::whereNull('end_date')
            ->with('unit', 'tenant')
            ->get();

        $calculator = new ElectricityCalculator();

        foreach ($tenancies as $tenancy) {
            $unit = $tenancy->unit;
            $tenant = $tenancy->tenant;

            // Skip if bill already exists
            if (Bill::where('tenant_id', $tenant->id)->where('unit_id', $unit->id)->where('month', $month)->exists()) {
                $this->info("Bill already exists for {$tenant->name} ({$unit->unit_no})");
                continue;
            }

            $rent = $unit->monthly_rent;
            $electricity = 0;

            if ($unit->type === 'shop' && $unit->has_electricity) {
                try {
                    $result = $calculator->calculateForMonth($month);
                    $electricity = $result['per_shop_cost'] ?? 0;
                } catch (\Exception $e) {
                    $this->warn("Meter reading missing for {$unit->unit_no}, electricity skipped.");
                }
            }

            $total = $rent + $electricity;

            Bill::create([
                'invoice_no' => strtoupper(Str::random(8)),
                'tenant_id' => $tenant->id,
                'unit_id' => $unit->id,
                'month' => $month,
                'rent_amount' => $rent,
                'electricity_amount' => $electricity,
                'total_amount' => $total,
                'issue_date' => Carbon::now()->toDateString(),
                'due_date' => Carbon::now()->addDays(10)->toDateString(),
            ]);

            $this->info("Bill created for {$tenant->name} ({$unit->unit_no})");
        }

        $this->info('Monthly bills generation completed.');
    }
}
