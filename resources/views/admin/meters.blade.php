<x-layouts.app>

    <!-- ADD / EDIT FORM -->
    <div class="mx-auto max-w-7xl py-2">
        <x-status-message />
    </div>

    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <h2 class="mb-4 text-lg font-semibold">
            {{ $editMeter ? 'Edit Meter' : 'Add Meter' }}
        </h2>

        <form method="POST" action="{{ $editMeter ? route('meters.update', $editMeter) : route('meters.store') }}">
            @csrf
            @if ($editMeter)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                <!-- Property -->
                <div>
                    <label class="mb-1 block text-sm font-medium">Select Unit / Shop (Electricity Only)</label>
                    <select class="w-full rounded border px-3 py-2 text-white dark:bg-gray-700" name="unit_id">
                        <option value="">-- Choose a Unit --</option>
                        @foreach ($units as $unit)
                            {{-- Only show the option if has_electricity is true --}}
                            @if ($unit->has_electricity)
                                <option value="{{ $unit->id }}" @selected(old('unit_id', $editMeter->unit_id ?? '') == $unit->id)>
                                    {{ $unit->unit_no }} ({{ ucfirst($unit->type) }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('unit_id')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meter No -->
                <div>
                    <label class="mb-1 block text-sm">Meter No</label>
                    <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="meter_no" type="text"
                        value="{{ old('meter_no', $editMeter->meter_no ?? '') }}">
                    @error('meter_no')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="mt-4">
                <button class="rounded bg-indigo-600 px-6 py-2 text-white">
                    {{ $editMeter ? 'Update' : 'Save' }}
                </button>

                @if ($editMeter)
                    <a class="ml-3 text-sm text-gray-500" href="{{ route('meters.index') }}">
                        Cancel
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- LIST -->
    <div
        class="mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white">Meters</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr
                        class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500 dark:bg-gray-900/50 dark:text-gray-400">
                        <th class="px-6 py-3 text-left font-semibold">ID</th>
                        <th class="px-6 py-3 text-left font-semibold">Unit ID</th>
                        <th class="px-6 py-3 text-left font-semibold">Meter No</th>
                        <th class="px-6 py-3 text-right font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($meters as $meter)
                        <tr class="transition-colors duration-200 hover:bg-indigo-50/50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 font-mono text-indigo-500 dark:text-indigo-400">#{{ $meter->id }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $meter->unit_id }}</td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $meter->meter_no }}</td>
                            <td class="px-6 py-4 text-right">
                                <a class="inline-flex items-center font-bold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
                                    href="{{ route('meters.edit', $meter) }}">
                                    <span>Edit</span>
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-12 text-center text-gray-500 dark:text-gray-400" colspan="4">
                                <div class="flex flex-col items-center">
                                    <svg class="mb-2 h-8 w-8 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p>No meters found in the database.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>
