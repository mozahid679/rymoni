<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meter;
use App\Models\Property;
use Illuminate\Http\Request;

class MeterController extends Controller
{
    public function index()
    {
        return view('admin.meters', [
            'meters'     => Meter::with('property')->latest()->get(),
            'properties' => Property::orderBy('name')->get(),
            'editMeter'  => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'meter_no'    => 'required|string|unique:meters,meter_no',
        ]);

        Meter::create($data);

        return redirect()->route('meters.index')
            ->with('success', 'Meter created successfully.');
    }

    public function edit(Meter $meter)
    {
        return view('admin.meters', [
            'meters'     => Meter::with('property')->latest()->get(),
            'properties' => Property::orderBy('name')->get(),
            'editMeter'  => $meter,
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
