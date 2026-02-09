<x-layouts.app>

    <!-- STATUS -->
    <div class="mx-auto max-w-7xl py-2">
        <x-status-message />
    </div>

    <!-- ADD / EDIT FORM -->
    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <h2 class="mb-4 text-lg font-semibold">
            {{ $editTenancy ? 'Edit Tenancy' : 'Add Tenancy' }}
        </h2>

        <form method="POST"
            action="{{ $editTenancy ? route('tenancies.update', $editTenancy) : route('tenancies.store') }}">
            @csrf
            @if ($editTenancy)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                <!-- Property -->
                <div>
                    <label class="mb-1 block text-sm">Property</label>
                    <select class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="property_id">
                        <option value="">Select</option>
                        @foreach ($properties as $property)
                            <option value="{{ $property->id }}" @selected(old('property_id', $editTenancy->property_id ?? '') == $property->id)>
                                {{ $property->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('property_id')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unit -->
                <div>
                    <label class="mb-1 block text-sm">Unit</label>
                    <select class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="unit_id">
                        <option value="">Select</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" @selected(old('unit_id', $editTenancy->unit_id ?? '') == $unit->id)>
                                {{ $unit->unit_no }} ({{ ucfirst($unit->type) }})
                            </option>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tenant -->
                <div>
                    <label class="mb-1 block text-sm">Tenant</label>
                    <select class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="tenant_id">
                        <option value="">Select</option>
                        @foreach ($tenants as $tenant)
                            <option value="{{ $tenant->id }}" @selected(old('tenant_id', $editTenancy->tenant_id ?? '') == $tenant->id)>
                                {{ $tenant->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('tenant_id')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label class="mb-1 block text-sm">Start Date</label>
                    <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="start_date" type="date"
                        value="{{ old('start_date', $editTenancy->start_date ?? '') }}">
                    @error('start_date')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label class="mb-1 block text-sm">End Date (optional)</label>
                    <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="end_date" type="date"
                        value="{{ old('end_date', $editTenancy->end_date ?? '') }}">
                    @error('end_date')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <button class="rounded bg-indigo-600 px-6 py-2 text-white">
                    {{ $editTenancy ? 'Update' : 'Save' }}
                </button>

                @if ($editTenancy)
                    <a class="ml-3 text-sm text-gray-500" href="{{ route('tenancies.index') }}">Cancel</a>
                @endif
            </div>
        </form>
    </div>

    <!-- LIST -->
    <div class="mt-8 overflow-hidden rounded-xl border bg-white shadow dark:bg-gray-800">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900/50">
                <tr class="text-left text-xs uppercase text-gray-500">
                    <th class="px-6 py-3">Property</th>
                    <th class="px-6 py-3">Unit</th>
                    <th class="px-6 py-3">Tenant</th>
                    <th class="px-6 py-3">Start</th>
                    <th class="px-6 py-3">End</th>
                    <th class="px-6 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @forelse ($tenancies as $tenancy)
                    <tr class="hover:bg-indigo-50/50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-3">{{ $tenancy->property->name }}</td>
                        <td class="px-6 py-3">{{ $tenancy->unit->unit_no }}</td>
                        <td class="px-6 py-3">{{ $tenancy->tenant->name }}</td>
                        <td class="px-6 py-3">{{ $tenancy->start_date }}</td>
                        <td class="px-6 py-3">{{ $tenancy->end_date ?? 'Running' }}</td>
                        <td class="px-6 py-3 text-right">
                            <a class="font-bold text-indigo-600"
                                href="{{ route('tenancies.edit', $tenancy) }}">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="py-10 text-center text-gray-500" colspan="6">
                            No tenancies found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-layouts.app>
