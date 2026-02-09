<x-layouts.app>

    <div class="mx-auto max-w-7xl py-6">

        <h1 class="mb-6 text-2xl font-bold">Generate Monthly Bills</h1>

        <form class="space-y-4 rounded bg-white p-6 shadow dark:bg-gray-800" action="{{ route('bills.generate.run') }}"
            method="POST">
            @csrf
            <div>
                <label class="mb-1 block font-medium">Month</label>
                <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="month" type="month"
                    value="{{ now()->format('Y-m') }}" required>
            </div>

            <button class="rounded bg-indigo-600 px-6 py-2 text-white" type="submit">Generate Bills</button>
        </form>

    </div>

</x-layouts.app>
