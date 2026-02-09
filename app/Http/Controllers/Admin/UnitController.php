<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Property;
use Illuminate\Http\Request;

class UnitController extends Controller
{
   public function index()
   {
      return view('admin.units', [
         'units'        => Unit::with('property')->latest()->get(),
         'properties'   => Property::orderBy('name')->get(),
         'editUnit'     => null,
      ]);
   }

   public function store(Request $request)
   {
      $data = $request->validate([
         'property_id'      => 'required|exists:properties,id',
         'unit_no'          => 'required|string|max:50',
         'type'             => 'required|in:room,shop',
         'monthly_rent'     => 'required|numeric|min:0',
         'has_electricity'  => 'nullable|boolean',
      ]);


      $data['has_electricity'] = $request->has('has_electricity');

      Unit::create($data);

      return redirect()->route('units.index')->with('success', 'Unit created successfully.');
   }

   public function edit(Unit $unit)
   {
      return view('admin.units', [
         'units'      => Unit::with('property')->latest()->get(),
         'properties' => Property::orderBy('name')->get(),
         'editUnit'   => $unit,
      ]);
   }

   public function update(Request $request, Unit $unit)
   {
      $data = $request->validate([
         'property_id'      => 'required|exists:properties,id',
         'unit_no'          => 'required|string|max:50',
         'type'             => 'required|in:room,shop',
         'monthly_rent'     => 'required|numeric|min:0',
         'has_electricity'  => 'nullable|boolean',
      ]);

      $data['has_electricity'] = $request->has('has_electricity');

      $unit->update($data);

      return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
   }
}
