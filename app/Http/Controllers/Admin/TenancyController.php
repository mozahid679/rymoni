<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenancy;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenancyController extends Controller
{
   public function index(Request $request, Tenancy $editTenancy = null)
   {
      $editUnitId = $editTenancy?->unit_id;

      $properties = Property::with(['units' => function ($query) use ($editUnitId) {

         $query->where(function ($q) use ($editUnitId) {

            $q->whereDoesntHave('tenancies', function ($t) {
               $t->where('status', 'active');
            });

            if ($editUnitId) {
               $q->orWhere('id', $editUnitId);
            }
         });
      }])->get();

      return view('admin.tenancies', [
         'tenancies'   => Tenancy::with(['property', 'unit', 'tenant'])->latest()->paginate(2),
         'properties'  => $properties,
         'tenants'     => Tenant::all(),
         'editTenancy' => $editTenancy,
      ]);
   }

   public function store(Request $request)
   {
      $request->validate([
         'unit_id' => 'required|exists:units,id',
         // other validation...
      ]);

      $unit = Unit::where('id', $request->unit_id)
         ->whereDoesntHave('tenancies', function ($q) {
            $q->where('status', 'active');
         })
         ->first();

      if (!$unit) {
         return back()->withErrors([
            'unit_id' => 'This unit is already booked.'
         ]);
      }

      Tenancy::create($request->all());

      return back()->with('success', 'Tenancy created successfully.');
   }

   // public function store(Request $request)
   // {
   //    $data = $request->validate([
   //       'property_id' => 'required|exists:properties,id',
   //       'unit_id'     => 'required|exists:units,id',
   //       'tenant_id'   => 'required|exists:tenants,id',
   //       'start_date'  => 'required|date',
   //       'end_date'    => 'nullable|date|after_or_equal:start_date',
   //    ]);

   //    Tenancy::create($data);

   //    return redirect()
   //       ->route('tenancies.index')
   //       ->with('success', 'Tenancy created successfully.');
   // }

   public function getUnits(Property $property)
   {
      // Return only the necessary fields
      return response()->json(
         $property->units()->select('id', 'unit_no', 'type')->get()
      );
   }

   public function edit(Tenancy $tenancy)
   {
      return view('admin.tenancies', [
         'tenancies' => Tenancy::with(['property', 'unit', 'tenant'])->latest()->get(),
         'properties' => Property::all(),
         'units' => Unit::all(),
         'tenants' => Tenant::all(),
         'editTenancy' => $tenancy,
      ]);
   }

   public function update(Request $request, Tenancy $tenancy)
   {
      $data = $request->validate([
         'property_id' => 'required|exists:properties,id',
         'unit_id'     => 'required|exists:units,id',
         'tenant_id'   => 'required|exists:tenants,id',
         'start_date'  => 'required|date',
         'end_date'    => 'nullable|date|after_or_equal:start_date',
      ]);

      $tenancy->update($data);

      return redirect()
         ->route('tenancies.index')
         ->with('success', 'Tenancy updated successfully.');
   }
}
