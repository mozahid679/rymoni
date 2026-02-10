<x-layouts.app>

    <div class="mx-auto max-w-7xl">
        <x-status-message />
    </div>

    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <h2 class="mb-4 text-lg font-semibold">
            {{ $editBill ? 'Edit Bill' : 'Add Bill' }}
        </h2>

        <form method="POST" action="{{ $editBill ? route('bills.update', $editBill) : route('bills.store') }}">

            @csrf
            @if ($editBill)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @unless ($editBill)
                    <div>
                        <label class="mb-1 block text-sm">Tenancy</label>
                        <select class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="tenancy_id" required>
                            <option value="">Select tenancy</option>
                            @foreach ($tenancies as $tenancy)
                                <option value="{{ $tenancy->id }}">
                                    {{ $tenancy->tenant->name }}
                                    — {{ $tenancy->unit->unit_no }}
                                    ({{ $tenancy->unit->property->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('tenancy_id')
                            <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm">Month (YYYY-MM)</label>
                        <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="month" type="month"
                            value="{{ old('month') }}" required>
                        @error('month')
                            <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                @endunless

                <div>
                    <label class="mb-1 block text-sm">Rent Amount</label>
                    <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="rent_amount" type="number"
                        value="{{ old('rent_amount', $editBill->rent_amount ?? '') }}" required step="0.01">
                    @error('rent_amount')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm">Electricity Amount</label>
                    <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="electricity_amount"
                        type="number" value="{{ old('electricity_amount', $editBill->electricity_amount ?? '') }}"
                        step="0.01">
                    @error('electricity_amount')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm">Issue Date</label>
                    <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="issue_date" type="date"
                        value="{{ old('issue_date', now()->toDateString()) }}">
                    @error('issue_date')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm">Due Date</label>
                    <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="due_date" type="date"
                        value="{{ old('due_date', now()->addDays(7)->toDateString()) }}">
                    @error('due_date')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                @if ($editBill)
                    <div>
                        <label class="mb-1 block text-sm">Status</label>
                        <select class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="status">
                            @foreach (['unpaid', 'partial', 'paid'] as $status)
                                <option value="{{ $status }}" @selected($editBill->status === $status)>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <button class="rounded bg-indigo-600 px-6 py-2 text-white">
                    {{ $editBill ? 'Update' : 'Generate Bill' }}
                </button>

                @if ($editBill)
                    <a class="ml-3 text-sm text-gray-500" href="{{ route('bills.index') }}">
                        Cancel
                    </a>
                @endif
            </div>

            @if ($errors->any())
                <div class="mb-4 rounded bg-red-50 p-3 text-sm text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>
    </div>

    <div
        class="mt-8 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div
            class="flex flex-col justify-between gap-4 border-b px-6 py-4 md:flex-row md:items-center dark:border-gray-700">
            <h2 class="text-lg font-bold">Bills</h2>

            <form class="flex items-center gap-2" action="{{ url()->current() }}" method="GET">
                <input class="rounded border border-gray-300 px-3 py-1.5 text-sm dark:border-gray-600 dark:bg-gray-700"
                    name="filter_month" type="month" value="{{ request('filter_month') }}">
                <button class="rounded bg-indigo-600 px-4 py-1.5 text-sm font-semibold text-white hover:bg-indigo-500"
                    type="submit">
                    Filter
                </button>
                @if (request('filter_month'))
                    <a class="text-xs text-red-500 hover:underline" href="{{ url()->current() }}">Clear</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-3 text-left">#</th>
                        <th class="px-6 py-3 text-left">View Invoice</th>
                        <th class="px-6 py-3 text-left">Invoice No</th>
                        <th class="px-6 py-3 text-left">Tenant</th>
                        <th class="px-6 py-3 text-left">Billing Month</th>
                        <th class="px-6 py-3 text-left">Total</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @forelse($bills as $bill)
                        <tr class="hover:bg-indigo-50/50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 font-mono">{{ $bill->id }}</td>
                            <td class="px-6 py-4 font-mono"><a class="font-semibold text-indigo-600"
                                    href="{{ route('bills.invoice', $bill) }}" target="_blank">
                                    Invoice
                                </a></td>
                            <td class="px-6 py-4 font-mono">{{ $bill->invoice_no }}</td>
                            <td class="px-6 py-4">{{ $bill->tenant->name }}</td>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($bill->month)->format('F, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                ৳{{ number_format($bill->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = match ($bill->status) {
                                        'paid'
                                            => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 border-green-200',
                                        'pending'
                                            => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 border-yellow-200',
                                        'overdue',
                                        'unpaid'
                                            => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border-red-200',
                                        default
                                            => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border-gray-200',
                                    };
                                @endphp

                                <span
                                    class="{{ $statusClasses }} inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-bold">
                                    {{ ucfirst($bill->status) }}

                                    @if ($bill->status === 'paid' || ($bill->paid_at || $bill->payment_date))
                                        <span class="ml-1 border-l border-current pl-1 font-normal opacity-80">
                                            —
                                            {{ \Carbon\Carbon::parse($bill->paid_at ?? $bill->payment_date)->format('d M Y') }}
                                        </span>
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a class="font-bold text-indigo-600" href="{{ route('bills.edit', $bill) }}">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-12 text-center text-gray-500" colspan="6">
                                No bills generated yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="border-t bg-gray-50 px-6 py-4 dark:bg-gray-900/50">
                {{ $bills->links() }}
            </div </div>
        </div>

</x-layouts.app>
