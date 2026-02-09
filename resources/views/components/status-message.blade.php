@if (session('success'))
    <div class="fixed right-5 top-5 z-[100] w-full max-w-sm" x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
        {{-- Smooth animations --}} x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-10" {{-- Fixed positioning to avoid layout shift --}}>
        <div
            class="flex items-center justify-between rounded-xl border border-emerald-500/30 bg-emerald-900/90 p-4 text-emerald-400 shadow-2xl backdrop-blur-xl">
            <div class="flex items-center">
                <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-sm font-bold tracking-wide">{{ session('success') }}</p>
            </div>

            <button class="ml-4 text-emerald-500/50 transition-colors hover:text-emerald-300" @click="show = false">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
@endif
