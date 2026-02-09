<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meter;
use App\Models\MeterReading;
use Illuminate\Http\Request;

class MeterReadingController extends Controller
{
    public function index()
    {
        return view('admin.meter-readings', [
            'readings' => MeterReading::with('meter.property')->latest()->get(),
            'meters'   => Meter::with('property')->get(),
            'editReading' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'meter_id' => 'required|exists:meters,id',
            'month' => 'required|string',
            'previous_unit' => 'required|integer|min:0',
            'current_unit' => 'required|integer|min:0',
            'per_unit_price' => 'required|numeric|min:0',
        ]);

        MeterReading::create($data);

        return redirect()->route('meter-readings.index')
            ->with('success', 'Meter reading created successfully.');
    }

    public function edit(MeterReading $reading)
    {
        return view('admin.meter-readings', [
            'readings' => MeterReading::with('meter.property')->latest()->get(),
            'meters'   => Meter::with('property')->get(),
            'editReading' => $reading,
        ]);
    }

    public function update(Request $request, MeterReading $reading)
    {
        $data = $request->validate([
            'meter_id' => 'required|exists:meters,id',
            'month' => 'required|string',
            'previous_unit' => 'required|integer|min:0',
            'current_unit' => 'required|integer|min:0',
            'per_unit_price' => 'required|numeric|min:0',
        ]);

        $reading->update($data);

        return redirect()->route('meter-readings.index')
            ->with('success', 'Meter reading updated successfully.');
    }
}
