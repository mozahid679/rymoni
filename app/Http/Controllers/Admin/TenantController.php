<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        return view('admin.tenants', [
            'tenants'      => Tenant::latest()->get(),
            'editTenant'   => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:tenants|max:255',
        ]);

        Tenant::create($data);

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant created successfully.');
    }

    public function edit(Tenant $tenant)
    {
        return view('admin.tenants', [
            'tenants'    => Tenant::latest()->get(),
            'editTenant' => $tenant,
        ]);
    }

    public function update(Request $request, Tenant $tenant)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $tenant->update($data);

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant updated successfully.');
    }
}
