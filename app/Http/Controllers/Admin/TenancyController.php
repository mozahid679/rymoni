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
   public function index()
   {
      return view('admin.tenancies', [
         'tenancies' => Tenancy::with(['property', 'unit', 'tenant'])->latest()->get(),
         'properties' => Property::all(),
         'units' => Unit::all(),
         'tenants' => Tenant::all(),
         'editTenancy' => null,
      ]);
   }

   public function store(Request $request)
   {
      $data = $request->validate([
         'property_id' => 'required|exists:properties,id',
         'unit_id'     => 'required|exists:units,id',
         'tenant_id'   => 'required|exists:tenants,id',
         'start_date'  => 'required|date',
         'end_date'    => 'nullable|date|after_or_equal:start_date',
      ]);

      Tenancy::create($data);

      return redirect()
         ->route('tenancies.index')
         ->with('success', 'Tenancy created successfully.');
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
