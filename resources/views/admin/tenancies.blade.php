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

            <div class="grid grid-cols-1 gap-4 md:grid-cols-6">

                <div class="contents" x-data="{
                    propertyId: '{{ old('property_id', $editTenancy->property_id ?? '') }}',
                    unitId: '{{ old('unit_id', $editTenancy->unit_id ?? '') }}',
                    properties: @js($properties),
                    loading: false,
                
                    changeProperty() {
                        this.loading = true
                        this.unitId = ''
                
                        setTimeout(() => {
                            this.loading = false
                        }, 400) // smooth UX delay
                    }
                }">

                    <!-- Property -->
                    <div>
                        <label class="mb-1 block text-sm">Property</label>
                        <select class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="property_id"
                            x-model="propertyId" @change="changeProperty()">

                            <option value="">Select</option>

                            <template x-for="property in properties" :key="property.id">
                                <option :value="property.id" x-text="property.name"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Unit -->
                    <div class="relative">
                        <label class="mb-1 block text-sm">Unit</label>

                        <!-- Spinner -->
                        <div class="absolute inset-0 z-10 flex items-center justify-center rounded bg-white/60 dark:bg-gray-800/60"
                            x-show="loading">
                            <svg class="h-6 w-6 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z">
                                </path>
                            </svg>
                        </div>

                        <select class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="unit_id" x-model="unitId"
                            :disabled="loading">

                            <option value="">Select</option>

                            <template x-for="unit in properties.find(p => p.id == propertyId)?.units || []"
                                :key="unit.id">

                                <option :value="unit.id"
                                    x-text="unit.unit_no + ' (' + unit.type.charAt(0).toUpperCase() + unit.type.slice(1) + ')'">
                                </option>

                            </template>

                        </select>
                    </div>

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

                <div class="mt-4 self-center">
                    <button class="rounded bg-indigo-600 px-6 py-2 text-white">
                        {{ $editTenancy ? 'Update Tenancy' : 'Add Tenancy' }}
                    </button>

                    @if ($editTenancy)
                        <a class="ml-3 text-sm text-gray-500" href="{{ route('tenancies.index') }}">Cancel</a>
                    @endif
                </div>
            </div>
        </form>
    </div>


    <div
        class="mt-8 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div
            class="flex flex-col justify-between gap-4 border-b px-6 py-4 md:flex-row md:items-center dark:border-gray-700">
            <h2 class="text-lg font-bold">Tenancies</h2>
        </div>
        <!-- LIST -->
        <div class="overflow-hidden rounded-xl bg-white shadow dark:bg-gray-800">
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
    </div>
    <div class="mt-4">
        {{ $tenancies->appends(request()->query())->links() }}
    </div>

</x-layouts.app>
