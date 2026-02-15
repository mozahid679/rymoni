<x-layouts.app>

    <!-- STATUS -->
    <div class="mx-auto max-w-7xl py-2">
        <x-status-message />
    </div>

    <!-- ADD / EDIT FORM -->
    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <h2 class="mb-4 text-lg font-semibold">
            {{ $editReading ? 'Edit Meter Reading' : 'Add Meter Reading' }}
        </h2>

        <form method="POST"
            action="{{ $editReading ? route('meter-readings.update', $editReading) : route('meter-readings.store') }}">

            @csrf
            @if ($editReading)
                @method('PUT')
            @endif

            <div x-data="{
                previous: {{ old('previous_unit', $editReading->previous_unit ?? 0) }},
                current: {{ old('current_unit', $editReading->current_unit ?? 0) }},
                price: {{ old('per_unit_price', $editReading->per_unit_price ?? 14) }},
            
                // This is the logic for your calculator
                get total() {
                    let diff = this.current - this.previous;
                    return diff > 0 ? (diff * this.price).toFixed(2) : '0.00';
                }
            }">

                <div class="grid grid-cols-1 gap-4 md:grid-cols-6">
                    {{-- Shop Selection --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium">Shop</label>

                        <select class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="meter_id"
                            x-on:change="previous = $event.target.selectedOptions[0].dataset.last || 0">
                            @if ($meters->isEmpty())
                                <option value="" disabled>No shops found in database!</option>
                            @else
                                @foreach ($meters as $meter)
                                    <option data-last="{{ $meter->lastReading->current_unit ?? 0 }}"
                                        value="{{ $meter->id }}" @selected(old('meter_id', $editReading->meter_id ?? '') == $meter->id)>
                                        {{ $meter->unit->unit_no ?? 'N/A' }} ({{ $meter->meter_no }})
                                    </option>
                                @endforeach
                            @endif

                        </select>
                        @error('meter_id')
                            <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Month --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium">Month</label>
                        <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="month" type="month"
                            value="{{ old('month', $editReading->month ?? '') }}">
                        @error('month')
                            <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Per Unit Price --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium">Per Unit Price</label>
                        <div x-data="{ price: {{ old('per_unit_price', $editReading->per_unit_price ?? 14) }} }">
                            <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="per_unit_price"
                                type="number" x-model="price" step="0.01">
                        </div>
                        @error('per_unit_price')
                            <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Previous Unit --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium">Previous Unit</label>
                        <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="previous_unit"
                            type="number" x-model="previous">
                        @error('previous_unit')
                            <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Current Unit --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium">Current Unit</label>
                        <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="current_unit"
                            type="number" x-model="current">
                        @error('current_unit')
                            <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-4">
                        <div>
                            <div class="w-full overflow-hidden">
                                <div class="rounded border border-indigo-500/20 bg-indigo-500/10 p-2">
                                    <p class="font-semibold tracking-wider text-indigo-400">Estimated
                                        Total
                                    </p>
                                    <p class="font-mono text-white">৳<span x-text="total"></span></p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button class="rounded bg-indigo-600 px-6 py-2 text-white">
                                {{ $editReading ? 'Update' : 'Save' }}
                            </button>

                            @if ($editReading)
                                <a class="ml-3 text-sm text-gray-500"
                                    href="{{ route('meter-readings.index') }}">Cancel</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- LIST -->
    <div class="mt-6 overflow-hidden rounded-xl border bg-white shadow dark:bg-gray-800">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900/50">
                <tr>
                    <th class="px-4 py-3 text-left">#</th>
                    <th class="px-4 py-3 text-left">Shop No</th>
                    <th class="px-4 py-3 text-left">Month</th>
                    <th class="px-4 py-3 text-left">Used Unit</th>
                    <th class="px-4 py-3 text-left">Total</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @foreach ($readings as $reading)
                    <tr>
                        <td class="px-4 py-3">
                            {{ $reading->meter->unit->id }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $reading->meter->unit->unit_no }}
                        </td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($reading->month)->format('F, Y') }}</td>
                        <td class="px-4 py-3">{{ $reading->used_unit }}</td>
                        <td class="px-4 py-3 font-semibold">
                            ৳{{ number_format($reading->total_amount, 2) }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a class="font-bold text-indigo-600"
                                href="{{ route('meter-readings.edit', $reading) }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-layouts.app>
