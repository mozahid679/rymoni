<x-layouts.app>
    <div class="space-y-6 pb-12">

        {{-- TOP BAR: Glassmorphism Style --}}
        <div
            class="flex flex-wrap items-center justify-between gap-4 rounded-3xl border border-white bg-white/50 p-6 backdrop-blur-md dark:border-gray-700 dark:bg-gray-800/50">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Executive Summary</h1>
                <p class="font-medium text-indigo-600 dark:text-indigo-400">Analytics for
                    {{ \Carbon\Carbon::parse($month)->format('F Y') }}</p>
            </div>
            <form class="flex items-center gap-3" method="GET">
                <input
                    class="rounded-xl border-none bg-white px-4 py-2 shadow-inner focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                    name="month" type="month" value="{{ $month }}">
                <button
                    class="rounded-xl bg-indigo-600 px-6 py-2 font-bold text-white shadow-lg shadow-indigo-200 transition-transform hover:bg-indigo-700 active:scale-95 dark:shadow-none">
                    Generate
                </button>
            </form>
        </div>

        {{-- VISUAL SECTION: Charts & Key Totals --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Financial Chart --}}
            <div
                class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm lg:col-span-2 dark:border-gray-700 dark:bg-gray-800">
                <h3 class="mb-4 flex items-center gap-2 text-lg font-bold">
                    <span class="rounded-lg bg-indigo-100 p-2 text-indigo-600">📊</span> Revenue Distribution
                </h3>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            {{-- Big Status Cards --}}
            <div class="flex flex-col gap-4">
                <div
                    class="relative flex-1 overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-600 p-6 text-white shadow-lg">
                    <div class="relative z-10">
                        <p class="font-medium text-emerald-100">Total Collected</p>
                        <p class="mt-1 text-4xl font-black">৳{{ number_format($totalPaid, 0) }}</p>
                    </div>
                    <div class="absolute bottom-0 right-4 text-8xl font-black italic text-white/10">PAID</div>
                </div>

                <div
                    class="relative flex-1 overflow-hidden rounded-3xl bg-gradient-to-br from-blue-500 to-indigo-600 p-6 text-white shadow-lg">
                    <div class="relative z-10">
                        <p class="font-medium text-emerald-100">Total Billed</p>
                        <p class="mt-1 text-4xl font-black">৳{{ number_format($totalBilled, 0) }}</p>
                    </div>
                    <div class="absolute bottom-0 right-4 text-8xl font-black italic text-white/10">Total</div>
                </div>



                <div
                    class="relative flex-1 overflow-hidden rounded-3xl bg-gradient-to-br from-rose-500 to-orange-600 p-6 text-white shadow-lg">
                    <div class="relative z-10">
                        <p class="font-medium text-rose-100">Total Pending</p>
                        <p class="mt-1 text-4xl font-black">৳{{ number_format($totalDue, 0) }}</p>
                    </div>
                    <div class="absolute bottom-0 right-4 text-8xl font-black italic text-white/10">DUE</div>
                </div>
            </div>
        </div>

        {{-- DETAILED CARDS: Units & Energy --}}
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            @php
                $stats = [
                    ['Properties', $totalProperties, '🏢', 'bg-blue-50 text-blue-700'],
                    ['Active Units', $totalUnits, '🔑', 'bg-purple-50 text-purple-700'],
                    ['Active Leases', $activeTenancies, '🤝', 'bg-amber-50 text-amber-700'],
                    ['Power Usage', $totalUnitsConsumed . ' kWh', '⚡', 'bg-yellow-50 text-yellow-700'],
                ];
            @endphp
            @foreach ($stats as [$label, $val, $emoji, $style])
                <div
                    class="rounded-2xl border border-gray-100 bg-white p-4 transition-shadow hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">{{ $emoji }}</span>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400">{{ $label }}</p>
                            <p class="text-xl font-black dark:text-white">{{ $val }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- DATA TABLES: High Contrast & Details --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

            {{-- Billing Table --}}
            <div
                class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between border-b border-gray-50 px-6 py-4 dark:border-gray-700">
                    <h3 class="font-black text-gray-800 dark:text-white">Detailed Billing</h3>
                    <span
                        class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold text-indigo-700">{{ count($bills) }}
                        Records</span>
                </div>
                <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm dark:border-gray-700">
                    <table class="w-full text-sm leading-tight">
                        <thead
                            class="bg-gray-50/80 text-xs font-bold uppercase text-gray-500 dark:bg-gray-900/80 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-4 text-left">Recipient & Location</th>
                                <th class="px-6 py-4 text-right">Breakdown</th>
                                <th class="px-6 py-4 text-right">Total Amount</th>
                                <th class="px-6 py-4 text-center">Payment Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white dark:divide-gray-800 dark:bg-gray-900">
                            @foreach ($bills as $bill)
                                <tr class="group transition-all hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10">
                                    {{-- Column 1: Tenant & Property Details --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-tr from-indigo-400 to-indigo-700 font-bold text-white shadow-sm">
                                                {{ strtoupper(substr($bill->tenant?->name ?? 'N', 0, 1)) }}
                                            </div>
                                            <div class="flex flex-col gap-0.5">
                                                {{-- 1. Primary: Tenant Name --}}
                                                <div
                                                    class="text-base font-semibold leading-none text-gray-900 dark:text-white">
                                                    {{ $bill->tenant?->name ?? 'Unknown Tenant' }}
                                                </div>

                                                {{-- 3. Tertiary: Unit Details --}}
                                                <div
                                                    class="flex items-center gap-1 text-[11px] font-bold italic text-gray-400 dark:text-gray-500">
                                                    <svg class="h-3 w-3 text-gray-300" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                    Unit {{ $bill->unit?->unit_no ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Column 2: Financial Breakdown (Mini-Table style) --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-block text-xs">
                                            <div class="flex justify-between gap-4 text-gray-500">
                                                <span>Rent:</span>
                                                <span
                                                    class="font-mono font-bold text-gray-700 dark:text-gray-300">{{ number_format($bill->rent_amount, 2) }}</span>
                                            </div>

                                            @php
                                                $reading = $meterReadings->firstWhere('meter.unit_id', $bill->unit_id);
                                                $unitsUsed = $reading ? $reading->total_units : 0;
                                            @endphp

                                            <div
                                                class="group relative mt-1 flex flex-col gap-1 rounded-lg bg-gray-50 py-2 dark:bg-gray-800/50">
                                                <div class="flex items-center justify-around text-gray-500">
                                                    <span>Usages: </span>
                                                    <span class="font-mono text-amber-700 dark:text-amber-400">
                                                        {{ number_format($unitsUsed, 1) }} <span
                                                            class="text-[9px]">Units</span>
                                                    </span>
                                                </div>

                                                {{-- Visual Consumption Bar --}}
                                                <div
                                                    class="h-1.5 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                                    <div class="h-full bg-gradient-to-r from-amber-400 to-orange-500 transition-all duration-500"
                                                        style="width: {{ min(100, ($unitsUsed / 500) * 100) }}%"></div>
                                                </div>

                                                {{-- Tooltip-style detail on hover --}}
                                                <div
                                                    class="mt-1 flex justify-between text-[9px] font-bold text-gray-400 opacity-0 transition-opacity group-hover:opacity-100">
                                                    <span>Prev: {{ $reading->previous_unit ?? 0 }}</span>
                                                    <span>Curr: {{ $reading->current_unit ?? 0 }}</span>
                                                </div>
                                            </div>

                                            <div class="flex justify-between gap-4 text-gray-500">
                                                <span>Elect. Bill:</span>
                                                <span
                                                    class="font-mono font-bold text-gray-700 dark:text-gray-300">{{ number_format($bill->electricity_amount, 2) }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Column 3: Grand Total --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="text-lg font-black text-indigo-600 dark:text-indigo-400">
                                            ৳{{ number_format($bill->total_amount, 2) }}
                                        </div>
                                        <div class="text-[10px] font-medium text-gray-400">Invoice
                                            #{{ $bill->id + 1000 }}</div>
                                    </td>

                                    {{-- Column 4: Detailed Status & Date --}}
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex flex-col items-center gap-1">
                                            <span
                                                class="{{ $bill->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }} inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[10px] font-black uppercase tracking-widest">
                                                <span
                                                    class="{{ $bill->status === 'paid' ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }} h-1.5 w-1.5 rounded-full"></span>
                                                {{ $bill->status }}
                                            </span>
                                            @if ($bill->status === 'paid')
                                                <span class="text-[10px] font-bold italic text-gray-400">
                                                    {{ \Carbon\Carbon::parse($bill->paid_at)->format('d M, Y') }}
                                                </span>
                                            @else
                                                <span
                                                    class="text-[10px] font-bold uppercase text-rose-400">Overdue</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Column 5: Action Buttons --}}
                                    <td class="px-6 py-4 text-right">
                                        <div
                                            class="flex justify-end gap-2 opacity-0 transition-opacity group-hover:opacity-100">
                                            <button
                                                class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400"
                                                title="View Receipt">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="浸15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <button class="p-2 text-gray-400 hover:text-emerald-600"
                                                title="Print Invoice">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Electricity Table --}}
            <div
                class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-50 px-6 py-4 dark:border-gray-700">
                    <h3 class="font-black text-gray-800 dark:text-white">Meter Consumption Details</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50/50 text-xs uppercase text-gray-400 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-3 text-left">Meter/Unit</th>
                                <th class="px-6 py-3 text-center">Previous</th>
                                <th class="px-6 py-3 text-center">Current</th>
                                <th class="px-6 py-3 text-right">Consumed</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                            @foreach ($meterReadings as $reading)
                                <tr class="transition-colors hover:bg-gray-50/80 dark:hover:bg-gray-700/50">
                                    <td class="px-6 py-4">
                                        {{-- Using optional() or null-safe operator to prevent crashes if a meter is missing --}}
                                        <div class="font-bold dark:text-white">
                                            {{ $reading->meter?->unit?->unit_no ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-400">
                                            #{{ $reading->meter?->meter_no ?? 'No Meter' }}</div>
                                    </td>

                                    {{-- Make sure these match your database columns (usually current_unit or current_reading) --}}
                                    <td class="px-6 py-4 text-center text-gray-400">
                                        {{ number_format($reading->previous_unit, 1) }}
                                    </td>

                                    <td class="px-6 py-4 text-center font-medium dark:text-gray-200">
                                        {{ number_format($reading->current_unit, 1) }}
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <span
                                            class="rounded-md bg-indigo-100 px-2 py-1 font-mono font-bold text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                                            {{-- Use the accessor you created earlier! --}}
                                            {{ number_format($reading->total_units, 1) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- CHART JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'], // You can make this dynamic
                    datasets: [{
                        label: 'Revenue Stream',
                        data: [{{ $totalPaid * 0.2 }}, {{ $totalPaid * 0.5 }},
                            {{ $totalPaid * 0.8 }}, {{ $totalPaid }}
                        ],
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 4,
                        pointRadius: 5,
                        pointBackgroundColor: '#4f46e5'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-layouts.app>
