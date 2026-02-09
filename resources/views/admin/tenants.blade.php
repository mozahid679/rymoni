<x-layouts.app>

    <!-- SUCCESS MESSAGE -->
    <div class="mx-auto max-w-7xl py-2">
        <x-status-message />
    </div>

    <!-- ADD / EDIT FORM -->
    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <h2 class="mb-4 text-lg font-semibold">
            {{ $editTenant ? 'Edit Tenant' : 'Add Tenant' }}
        </h2>

        <form method="POST" action="{{ $editTenant ? route('tenants.update', $editTenant) : route('tenants.store') }}">
            @csrf
            @if ($editTenant)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                <!-- Name -->
                <div>
                    <label class="mb-1 block text-sm">Name</label>
                    <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="name" type="text"
                        value="{{ old('name', $editTenant->name ?? '') }}" required>

                    @error('name')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="mb-1 block text-sm">Phone</label>
                    <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="phone" type="text"
                        value="{{ old('phone', $editTenant->phone ?? '') }}">

                    @error('phone')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="mb-1 block text-sm">Email</label>
                    <input class="w-full rounded border px-3 py-2 dark:bg-gray-700" name="email" type="email"
                        value="{{ old('email', $editTenant->email ?? '') }}">

                    @error('email')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="mt-4">
                <button class="rounded bg-indigo-600 px-6 py-2 text-white">
                    {{ $editTenant ? 'Update' : 'Save' }}
                </button>

                @if ($editTenant)
                    <a class="ml-3 text-sm text-gray-500" href="{{ route('tenants.index') }}">Cancel</a>
                @endif
            </div>
        </form>
    </div>

    <!-- LIST -->
    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <h2 class="mb-4 text-lg font-semibold">Tenants</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr
                        class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500 dark:bg-gray-900/50 dark:text-gray-400">
                        <th class="px-6 py-3 text-left font-semibold">ID</th>
                        <th class="px-6 py-3 text-left font-semibold">Name</th>
                        <th class="px-6 py-3 text-left font-semibold">Phone</th>
                        <th class="px-6 py-3 text-left font-semibold">Email</th>
                        <th class="px-6 py-3 text-right font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($tenants as $tenant)
                        <tr class="transition-colors duration-200 hover:bg-indigo-50/50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 font-mono text-indigo-500 dark:text-indigo-400">
                                {{ $tenant->id }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $tenant->name }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $tenant->phone }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $tenant->email }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a class="inline-flex items-center font-bold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
                                    href="{{ route('tenants.edit', $tenant) }}"><span>Edit</span>
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-4 text-center text-gray-500" colspan="4">
                                No tenants found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
