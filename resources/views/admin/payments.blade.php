<x-layouts.app>

    <!-- STATUS -->
    <div class="mx-auto max-w-7xl py-2">
        <x-status-message />
    </div>

    <!-- ADD PAYMENT -->
    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <h2 class="mb-4 text-lg font-semibold">Add Payment</h2>

        <form method="POST" action="{{ route('payments.store') }}">
            @csrf


            <div x-data="{ selectedAmount: '' }">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-6">
                    <div>
                        <label class="mb-1 block text-sm">Bill</label>
                        <select class="w-full rounded border px-3 py-2 text-white dark:bg-gray-700" name="bill_id"
                            x-on:change="selectedAmount = $event.target.selectedOptions[0].dataset.amount || ''">
                            <option value="">Select Bill</option>
                            @foreach ($bills as $bill)
                                <option data-amount="{{ $bill->total_amount }}" value="{{ $bill->id }}">
                                    {{ $bill->invoice_no }} — {{ $bill->tenant->name }} ({{ $bill->total_amount }}-
                                    {{ \Carbon\Carbon::parse($bill->month)->format('F, Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('bill_id')
                            <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm">Amount</label>
                        <input class="w-full cursor-not-allowed rounded border bg-gray-100 px-3 py-2 dark:bg-gray-600"
                            name="amount" type="number" step="0.01" x-model="selectedAmount" readonly>
                        <p class="mt-1 text-xs text-gray-500">This amount is automatically pulled from the selected
                            bill.</p>
                        @error('amount')
                            <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm">Method</label>
                        <select class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="method">
                            <option value="cash">Cash</option>
                            <option value="bank">Bank</option>
                            <option value="bkash">Bkash</option>
                            <option value="nagad">Nagad</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm">Payment Date</label>
                        <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="payment_date"
                            type="date" value="{{ now()->toDateString() }}">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm">Received By</label>
                        <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="received_by"
                            type="text">
                    </div>

                    <div class="mt-5">
                        <button class="rounded bg-indigo-600 px-6 py-2 text-white">
                            Save Payment
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- LIST -->
    <div class="mt-6 overflow-hidden rounded-xl border bg-white shadow dark:bg-gray-800">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left">Invoice</th>
                    <th class="px-6 py-3 text-left">Tenant</th>
                    <th class="px-6 py-3 text-left">Amount</th>
                    <th class="px-6 py-3 text-left">Method</th>
                    <th class="px-6 py-3 text-left">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                    <tr class="border-t">
                        <td class="px-6 py-3">{{ $payment->bill->invoice_no }} -
                            <span class="text-xs text-gray-400">for</span> -
                            {{ \Carbon\Carbon::parse($bill->month)->format('F, Y') }}
                        </td>
                        <td class="px-6 py-3">{{ $payment->bill->tenant->name }}</td>
                        <td class="px-6 py-3">{{ $payment->amount }}</td>
                        <td class="px-6 py-3 capitalize">{{ $payment->method }}</td>
                        <td class="px-6 py-3">{{ $payment->payment_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-layouts.app>
