            <aside
                class="bg-sidebar text-sidebar-foreground sidebar-transition overflow-hidden border-r border-gray-200 dark:border-gray-700"
                :class="{ 'w-full md:w-64': sidebarOpen, 'w-0 md:w-16 hidden md:block': !sidebarOpen }">
                <!-- Sidebar Content -->
                <div class="flex h-full flex-col">
                    <!-- Sidebar Menu -->
                    <nav class="custom-scrollbar flex-1 overflow-y-auto py-4">
                        <ul class="space-y-1 px-2">
                            <!-- Dashboard -->
                            <x-layouts.sidebar-link href="{{ route('dashboard') }}" icon='fas-house'
                                :active="request()->routeIs('dashboard*')">Dashboard</x-layouts.sidebar-link>

                            <x-layouts.sidebar-link href="{{ route('properties.index') }}" icon='fas-building'
                                :active="request()->routeIs('properties*')">Properties</x-layouts.sidebar-link>

                            <x-layouts.sidebar-link href="{{ route('units.index') }}" icon='fas-shop'
                                :active="request()->routeIs('units*')">Units</x-layouts.sidebar-link>

                            <x-layouts.sidebar-link href="{{ route('meters.index') }}" icon='fas-bolt'
                                :active="request()->routeIs('meters*')">
                                Meter (Shop only)</x-layouts.sidebar-link>

                            <x-layouts.sidebar-link href="{{ route('meter-readings.index') }}" icon='fas-calculator'
                                :active="request()->routeIs('meter-readings*')">
                                Meter Readings</x-layouts.sidebar-link>

                            <x-layouts.sidebar-link href="{{ route('tenants.index') }}" icon='fas-users'
                                :active="request()->routeIs('tenants*')">Tenants</x-layouts.sidebar-link>

                            <x-layouts.sidebar-link href="{{ route('tenancies.index') }}" icon='fas-users'
                                :active="request()->routeIs('tenancies*')">Tenancies</x-layouts.sidebar-link>

                            <x-layouts.sidebar-link href="{{ route('bills.index') }}" icon='fas-money-bill'
                                :active="request()->routeIs('bills.index*')">
                                Bills</x-layouts.sidebar-link>

                            <x-layouts.sidebar-link href="{{ route('bills.generate') }}" icon='fas-file-invoice-dollar'
                                :active="request()->routeIs('bills.generate*')">
                                Auto Generate Bill</x-layouts.sidebar-link>

                            <x-layouts.sidebar-link href="{{ route('payments.index') }}" icon='fas-dollar-sign'
                                :active="request()->routeIs('payments*')">
                                Payments</x-layouts.sidebar-link>

                            <x-layouts.sidebar-link href="{{ route('reports.index') }}" icon='fas-chart-area'
                                :active="request()->routeIs('reports*')">
                                Reports</x-layouts.sidebar-link>

                            <x-layouts.sidebar-link href="{{ route('users.index') }}" icon='fas-user-gear'
                                :active="request()->routeIs('users*')">
                                System Users</x-layouts.sidebar-link>

                            <x-layouts.sidebar-link href="{{ route('settings.profile.edit') }}" icon='fas-gear'
                                :active="request()->routeIs('settings*')">
                                Settings</x-layouts.sidebar-link>

                            <!-- Example two level -->
                            {{-- <x-layouts.sidebar-two-level-link-parent title="Example two level" icon="fas-house"
                                :active="request()->routeIs('two-level*')">
                                <x-layouts.sidebar-two-level-link href="#" icon='fas-house'
                                    :active="request()->routeIs('two-level*')">Child</x-layouts.sidebar-two-level-link>
                            </x-layouts.sidebar-two-level-link-parent> --}}

                            <!-- Example three level -->
                            {{-- <x-layouts.sidebar-two-level-link-parent title="Example three level" icon="fas-house"
                                :active="request()->routeIs('three-level*')">
                                <x-layouts.sidebar-two-level-link href="#" icon='fas-house'
                                    :active="request()->routeIs('three-level*')">Single Link</x-layouts.sidebar-two-level-link>

                                <x-layouts.sidebar-three-level-parent title="Third Level" icon="fas-house"
                                    :active="request()->routeIs('three-level*')">
                                    <x-layouts.sidebar-three-level-link href="#" :active="request()->routeIs('three-level*')">
                                        Third Level Link
                                    </x-layouts.sidebar-three-level-link>
                                </x-layouts.sidebar-three-level-parent>
                            </x-layouts.sidebar-two-level-link-parent> --}}
                        </ul>
                    </nav>
                </div>
            </aside>
