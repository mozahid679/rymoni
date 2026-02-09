<x-layouts.app>

    <div class="mx-auto max-w-7xl py-2">
        <x-status-message />
    </div>

    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <h2 class="mb-4 text-lg font-semibold">
            {{ $editUnit ? 'Edit Unit' : 'Add Unit' }}
        </h2>
        <form method="POST" action="{{ $editUnit ? route('units.update', $editUnit) : route('units.store') }}">
            @csrf
            @if ($editUnit)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                <!-- Property -->
                <div>
                    <label class="mb-1 block text-sm">Property</label>
                    <select class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="property_id">
                        <option value="">Select property</option>
                        @foreach ($properties as $property)
                            <option value="{{ $property->id }}" @selected(old('property_id', $editUnit->property_id ?? '') == $property->id)>
                                {{ $property->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('property_id')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unit No -->
                <div>
                    <label class="mb-1 block text-sm">Unit No</label>
                    <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="unit_no" type="text"
                        value="{{ old('unit_no', $editUnit->unit_no ?? '') }}">

                    @error('unit_no')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label class="mb-1 block text-sm">Type</label>
                    <select class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="type">
                        <option value="room" @selected(old('type', $editUnit->type ?? '') == 'room')>Room</option>
                        <option value="shop" @selected(old('type', $editUnit->type ?? '') == 'shop')>Shop</option>
                    </select>

                    @error('type')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rent -->
                <div>
                    <label class="mb-1 block text-sm">Monthly Rent</label>
                    <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="monthly_rent" type="number"
                        value="{{ old('monthly_rent', $editUnit->monthly_rent ?? '') }}" step="0.01">

                    @error('monthly_rent')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Electricity -->
                <div class="mt-6 flex flex-col">
                    <div class="flex items-center">
                        <input name="has_electricity" type="hidden" value="0">

                        <input class="mr-2 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            id="has_electricity" name="has_electricity" type="checkbox" value="1"
                            @checked(old('has_electricity', $editUnit->has_electricity ?? false))>

                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300" for="has_electricity">
                            Has Electricity
                        </label>
                    </div>

                    @error('has_electricity')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <button class="rounded bg-indigo-600 px-6 py-2 text-white">
                    {{ $editUnit ? 'Update' : 'Save' }}
                </button>

                @if ($editUnit)
                    <a class="ml-3 text-sm text-gray-500" href="{{ route('units.index') }}">Cancel</a>
                @endif
            </div>
        </form>
    </div>

    <!-- LIST -->
    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <h2 class="mb-4 text-lg font-semibold">Units</h2>

        <table class="w-full text-sm">
            <thead>
                <tr class="border-b dark:border-gray-700">
                    <th class="py-2 text-left">Property</th>
                    <th class="py-2 text-left">Unit</th>
                    <th class="py-2 text-left">Type</th>
                    <th class="py-2 text-left">Rent</th>
                    <th class="py-2 text-center">Electric</th>
                    <th class="py-2 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($units as $unit)
                    <tr class="border-b dark:border-gray-700">
                        <td class="py-2">{{ $unit->property->name }}</td>
                        <td class="py-2">{{ $unit->unit_no }}</td>
                        <td class="py-2 capitalize">{{ $unit->type }}</td>
                        <td class="py-2">{{ number_format($unit->monthly_rent, 2) }}</td>
                        <td class="py-2 text-center">
                            {{ $unit->has_electricity ? 'Yes' : 'No' }}
                        </td>
                        <td class="py-2 text-right">
                            <a class="text-indigo-600" href="{{ route('units.edit', $unit) }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</x-layouts.app>
