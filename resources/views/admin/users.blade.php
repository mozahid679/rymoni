<x-layouts.app>
    <div class="min-h-screen bg-[#0b0f1a] py-4 font-sans text-slate-300">
        <!-- SUCCESS MESSAGE -->
        <div class="mx-auto max-w-7xl py-2">
            <x-status-message />
        </div>
        <div class="mx-auto max-w-6xl px-4">

            <div class="mb-8">
                <div class="group relative">
                    <div
                        class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-indigo-500 to-emerald-500 opacity-10 blur transition duration-1000 group-hover:opacity-20">
                    </div>

                    <div
                        class="relative rounded-3xl border border-slate-800 bg-slate-900/80 p-8 shadow-2xl backdrop-blur-xl">
                        <div class="mb-8 flex items-center space-x-3">
                            <div class="h-10 w-2 rounded-full bg-indigo-500"></div>
                            <h2 class="text-2xl font-bold tracking-tight text-white">
                                {{ isset($editingUser) ? 'Update Property Manager' : 'Register New Manager' }}
                            </h2>
                        </div>

                        <form class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                            action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}"
                            method="POST">

                            @csrf
                            @if (isset($user))
                                @method('PUT')
                            @endif

                            {{-- Full Name --}}
                            <div class="space-y-2">
                                <label class="ml-1 text-xs font-bold uppercase tracking-widest text-slate-500">Full
                                    Name</label>
                                <input
                                    class="w-full rounded-xl border border-slate-700 bg-slate-800/50 px-4 py-3 text-white outline-none transition-all placeholder:text-slate-600 focus:ring-2 focus:ring-indigo-500"
                                    name="name" type="text" value="{{ old('name', $user->name ?? '') }}"
                                    placeholder="John Doe"> {{-- Loads existing data or old input --}}

                                @error('name')
                                    <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email Address --}}
                            <div class="space-y-2">
                                <label class="ml-1 text-xs font-bold uppercase tracking-widest text-slate-500">Email
                                    Address</label>
                                <input
                                    class="w-full rounded-xl border border-slate-700 bg-slate-800/50 px-4 py-3 text-white outline-none transition-all placeholder:text-slate-600 focus:ring-2 focus:ring-indigo-500"
                                    name="email" type="email" value="{{ old('email', $user->email ?? '') }}"
                                    placeholder="john@example.com">

                                @error('email')
                                    <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="space-y-2">
                                <label class="ml-1 text-xs font-bold uppercase tracking-widest text-slate-500">
                                    Password {{ isset($user) ? '(Leave blank to keep current)' : '' }}
                                </label>
                                <input
                                    class="w-full rounded-xl border border-slate-700 bg-slate-800/50 px-4 py-3 text-white outline-none transition-all placeholder:text-slate-600 focus:ring-2 focus:ring-indigo-500"
                                    name="password" type="password" placeholder="••••••••">

                                @error('password')
                                    <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <div class="mt-4 flex justify-end md:col-span-2 lg:col-span-4">
                                <button
                                    class="group flex items-center rounded-xl bg-indigo-600 px-8 py-3 font-bold text-white shadow-lg shadow-indigo-500/20 transition-all hover:bg-indigo-500 active:scale-95"
                                    type="submit">
                                    <span>{{ isset($user) ? 'Save Changes' : 'Confirm Registration' }}</span>
                                    <svg class="ml-2 h-5 w-5 transition-transform group-hover:translate-x-1"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mb-6 flex items-center justify-between px-2">
                <h3 class="text-lg font-semibold text-slate-400">Current Directory</h3>
                <span
                    class="rounded-full border border-slate-700 bg-slate-800 px-3 py-1 font-mono text-xs text-indigo-400">
                    {{ count($users ?? []) }}
                </span>
                <span>Entries</span>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @forelse ($users as $user)
                    <div
                        class="group flex items-center justify-between rounded-2xl border border-slate-800 bg-slate-900/40 p-4 transition-colors hover:bg-slate-800/40">
                        <div class="flex items-center space-x-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-xl border border-slate-700 bg-slate-800 transition-colors group-hover:border-indigo-500/50">
                                <span
                                    class="font-mono font-bold text-indigo-400">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-white">{{ $user->name }}</h4>
                                <p class="text-sm text-slate-500">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <div class="flex items-center space-x-2">
                                <a class="rounded-lg p-2 text-slate-400 transition-colors hover:bg-slate-700 hover:text-indigo-400"
                                    href="{{ route('users.edit', $user) }}" title="Edit User">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>

                                <form action="{{ route('users.destroy', $user) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this user? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="rounded-lg p-2 text-slate-400 transition-colors hover:bg-slate-700 hover:text-red-400"
                                        type="submit" title="Delete User">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-10 text-center italic text-slate-600">No managers registered yet.</div>
                @endforelse
            </div>

        </div>
    </div>
</x-layouts.app>
