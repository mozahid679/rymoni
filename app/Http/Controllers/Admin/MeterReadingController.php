<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meter;
use App\Models\MeterReading;
use App\Models\Unit;
use Illuminate\Http\Request;

class MeterReadingController extends Controller
{
    public function index()
    {
        $meters = Meter::with(['unit', 'lastReading'])
            ->whereHas('unit', function ($query) {
                $query->where('type', 'like', 'shop');
            })
            ->get();

        $readings = MeterReading::with(['meter.unit'])
            ->latest()
            ->get();

        return view('admin.meter-readings', [
            'meters'      => $meters,
            'readings'    => $readings,
            'editReading' => null, // Explicitly null for the create form
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'meter_id' => 'required|exists:meters,id',
            'month' => 'required|date_format:Y-m',
            'previous_unit' => 'required|integer|min:0',
            'current_unit' => 'required|integer|gt:previous_unit',
            'per_unit_price' => 'required|numeric|min:0',
        ]);

        // Calculate total billable units and total amount
        $totalUnits = $data['current_unit'] - $data['previous_unit'];
        $data['total_amount'] = $totalUnits * $data['per_unit_price'];

        MeterReading::create($data);

        return redirect()
            ->route('meter-readings.index')
            ->with('success', 'Meter reading added successfully.Total amount: ' . number_format($data['total_amount'], 2));
    }

    public function edit(MeterReading $meterReading)
    {
        $meters = Meter::with('unit')
            ->whereHas('unit', function ($q) {
                // This handles 'shop', 'Shop', 'SHOP ', etc.
                $q->where('type', 'like', 'shop%');
            })
            ->get();

        $actualTypes = \App\Models\Unit::distinct()->pluck('type');

        dd($actualTypes);

        // Debugging: If list is still empty, this will tell us why
        if ($meters->isEmpty()) {
            $actualTypes = Unit::distinct()->pluck('type')->toArray();
            logger("Meters empty. Types found in DB: " . implode(', ', $actualTypes));
        }

        $readings = MeterReading::with('meter.unit')->latest()->get();

        return view('admin.meter-readings', [
            'meters' => $meters,
            'readings' => $readings,
            'editReading' => $meterReading,
        ]);
    }

    public function update(Request $request, MeterReading $meterReading)
    {
        $data = $request->validate([
            'meter_id' => 'required|exists:meters,id',
            'month' => 'required|date_format:Y-m',
            'previous_unit' => 'required|integer|min:0',
            'current_unit' => 'required|integer|gt:previous_unit',
            'per_unit_price' => 'required|numeric|min:0',
        ]);

        $meterReading->update($data);

        return redirect()
            ->route('meter-readings.index')
            ->with('success', 'Meter reading updated successfully.');
    }
}
