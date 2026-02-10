<footer class="mt-auto border-t border-gray-200 bg-white/80 backdrop-blur-md dark:border-gray-700 dark:bg-gray-800/80">
    <div class="px-6 py-3">
        <div class="flex flex-wrap items-center justify-between gap-4">

            <div class="flex items-center space-x-4 text-xs font-medium text-gray-500 dark:text-gray-400">
                <span class="hidden h-4 border-l border-gray-300 md:block dark:border-gray-600"></span>

                <div class="hidden md:block">
                    IP: <span class="text-gray-700 dark:text-gray-200">{{ request()->ip() }}</span>
                </div>

                <div class="flex items-center gap-1.5">
                    <span
                        class="{{ app()->environment('production') ? 'bg-red-500' : 'bg-green-500' }} h-1.5 w-1.5 rounded-full"></span>
                    <span class="uppercase">{{ app()->environment() }}</span>
                </div>

                <div class="hidden lg:block">
                    RAM: <span
                        class="text-gray-700 dark:text-gray-200">{{ round(memory_get_usage() / 1024 / 1024, 2) }}MB</span>
                </div>
            </div>

            <div class="flex items-center gap-2 font-mono text-xs font-bold text-blue-600 dark:text-blue-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span id="live-clock">Loading...</span>
            </div>

            <div class="hidden items-center gap-2 lg:flex">
                <span class="text-[10px] text-gray-400">PHP v{{ PHP_VERSION }}</span>
                <span
                    class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-900/30 dark:text-blue-400">
                    v1.1.0
                </span>
            </div>

            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-tighter text-gray-400">
                    <svg class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span>
                        Load: {{ number_format((microtime(true) - LARAVEL_START) * 1000, 2) }}ms
                    </span>
                </div>

                <div class="text-sm text-gray-500 dark:text-gray-400">Copyright &copy; {{ now()->year }}
                    <a href="/">
                        <span class="dark:text-green font-bold text-gray-600"> Rent</span><span
                            class="text-green-600">Manager</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        function updateDhakaClock() {
            const options = {
                timeZone: 'Asia/Dhaka',
                hour12: true,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };

            const formatter = new Intl.DateTimeFormat('en-US', options);
            document.getElementById('live-clock').textContent = formatter.format(new Date());
        }

        // Update every second
        setInterval(updateDhakaClock, 1000);
        // Initialize immediately
        updateDhakaClock();
    </script>
</footer>
