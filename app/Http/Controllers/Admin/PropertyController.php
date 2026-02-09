<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
   public function index()
   {
      return view('admin.properties', [
         'properties' => Property::latest()->get(),
         'editProperty' => null,
      ]);
   }

   public function store(Request $request)
   {
      $data = $request->validate([
         'name'    => 'required| unique:properties|string|max:255|min:3',
         'address' => 'nullable|string|min:3|max:255',
      ]);

      Property::create($data);

      return redirect()->route('properties.index')->with('success', 'Property created successfully.');
   }

   public function edit(Property $property)
   {
      return view('admin.properties', [
         'properties'   => Property::latest()->get(),
         'editProperty' => $property,
      ]);
   }

   public function update(Request $request, Property $property)
   {
      $data = $request->validate([
         'name'    => 'required|string|max:255',
         'address' => 'nullable|string',
      ]);

      $property->update($data);

      return redirect()->route('properties.index')->with('success', 'Property updated successfully.');
   }
}
