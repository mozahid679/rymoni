<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meter;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;

class MeterController extends Controller
{
    public function index()
    {
        return view('admin.meters', [
            'meters'    => Meter::with('unit')->latest()->get(),
            'units'     => Unit::where('has_electricity', true)
                ->orderBy('unit_no')
                ->get(),
            'editMeter' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'unit_id'  => 'required|exists:units,id',
            'meter_no'    => 'required|string|unique:meters,meter_no',
        ]);

        Meter::create($data);

        return redirect()->route('meters.index')
            ->with('success', 'Meter created successfully.');
    }

    public function edit(Meter $meter)
    {
        return view('admin.meters', [
            'meters'    => Meter::with('unit.property')->latest()->get(),
            'units'     => Unit::with('property')->orderBy('unit_no')->get(),
            'editMeter' => $meter,
        ]);
    }

    public function update(Request $request, Meter $meter)
    {
        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'meter_no'    => 'required|string|unique:meters,meter_no,' . $meter->id,
        ]);

        $meter->update($data);

        return redirect()->route('meters.index')
            ->with('success', 'Meter updated successfully.');
    }
}
