<x-layouts.app>
    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-950">

        <h1 class="mb-6 text-2xl font-bold text-gray-800 dark:text-white">
            Dashboard
        </h1>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">

            <!-- Total Units -->
            <div class="rounded-xl bg-white p-5 shadow dark:bg-gray-800">
                <p class="text-sm text-gray-500">Total Units</p>
                <p class="mt-2 text-3xl font-bold text-indigo-600">{{ $totalUnits }}</p>
            </div>

            <!-- Occupied -->
            <div class="rounded-xl bg-white p-5 shadow dark:bg-gray-800">
                <p class="text-sm text-gray-500">Occupied Units</p>
                <p class="mt-2 text-3xl font-bold text-green-600">{{ $occupiedUnits }}</p>
            </div>

            <!-- Vacant -->
            <div class="rounded-xl bg-white p-5 shadow dark:bg-gray-800">
                <p class="text-sm text-gray-500">Vacant Units</p>
                <p class="mt-2 text-3xl font-bold text-red-500">{{ $vacantUnits }}</p>
            </div>

            <!-- Tenants -->
            <div class="rounded-xl bg-white p-5 shadow dark:bg-gray-800">
                <p class="text-sm text-gray-500">Total Tenants</p>
                <p class="mt-2 text-3xl font-bold text-blue-600">{{ $totalTenants }}</p>
            </div>
        </div>

        <!-- Billing Summary -->
        <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-3">

            <div class="rounded-xl bg-indigo-50 p-6 dark:bg-indigo-900/30">
                <p class="text-sm text-indigo-700 dark:text-indigo-300">
                    Total Billed (This Month)
                </p>
                <p class="mt-2 text-2xl font-bold text-indigo-800 dark:text-indigo-200">
                    {{ number_format($totalBilledAmount, 2) }}
                </p>
            </div>

            <div class="rounded-xl bg-green-50 p-6 dark:bg-green-900/30">
                <p class="text-sm text-green-700 dark:text-green-300">
                    Total Paid
                </p>
                <p class="mt-2 text-2xl font-bold text-green-800 dark:text-green-200">
                    {{ number_format($totalPaidAmount, 2) }}
                </p>
            </div>

            <div class="rounded-xl bg-red-50 p-6 dark:bg-red-900/30">
                <p class="text-sm text-red-700 dark:text-red-300">
                    Total Due
                </p>
                <p class="mt-2 text-2xl font-bold text-red-800 dark:text-red-200">
                    {{ number_format($totalDueAmount, 2) }}
                </p>
            </div>

        </div>

    </div>

</x-layouts.app>
