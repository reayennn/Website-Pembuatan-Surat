<nav x-data="{ open: false }" class="bg-green-800 border-b border-green-900">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo / Brand -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="flex items-center space-x-2">
                        <div class="bg-yellow-400 rounded-full p-1">
                            <svg class="w-6 h-6 text-green-800" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/></svg>
                        </div>
                        <div class="leading-tight">
                            <div class="text-white font-bold text-sm">Desa Karombo</div>
                            <div class="text-green-300 text-xs">Layanan Surat Digital</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.penduduk.index')" :active="request()->routeIs('admin.penduduk.*')">
                            {{ __('Data Warga') }}
                        </x-nav-link>

                        <!-- Master Surat Dropdown -->
                        <div class="hidden sm:flex sm:items-center" x-data="{ openMaster: false }">
                            <div class="relative">
                                <button @click="openMaster = !openMaster" @click.outside="openMaster = false"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-green-100 hover:text-white focus:outline-none transition duration-150 ease-in-out"
                                    :class="openMaster ? 'text-yellow-300 border-yellow-400' : ''">
                                    Master Surat
                                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-show="openMaster" x-transition class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                    <a href="{{ route('admin.kop_surat.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kop Surat</a>
                                    <a href="{{ route('admin.tanda_tangan.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tanda Tangan</a>
                                </div>
                            </div>
                        </div>

                        <x-nav-link :href="route('admin.jenis_surat.index')" :active="request()->routeIs('admin.jenis_surat.*')">
                            {{ __('Jenis Surat') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.template_surat.index')" :active="request()->routeIs('admin.template_surat.*')">
                            {{ __('Template Surat') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.pengajuan_surat.index')" :active="request()->routeIs('admin.pengajuan_surat.*')">
                            {{ __('Pengajuan Surat') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('Kelola Pengguna') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.*')">
                            {{ __('Laporan') }}
                        </x-nav-link>
                    @elseif(Auth::user()->role === 'kepala_desa')
                        <x-nav-link :href="route('kades.dashboard')" :active="request()->routeIs('kades.dashboard')">
                            {{ __('Dashboard Kades') }}
                        </x-nav-link>
                        <x-nav-link :href="route('kades.persetujuan.index')" :active="request()->routeIs('kades.persetujuan.*')">
                            {{ __('Persetujuan Surat') }}
                        </x-nav-link>
                        <x-nav-link :href="route('kades.laporan.index')" :active="request()->routeIs('kades.laporan.*')">
                            {{ __('Laporan') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('pengajuan.index')" :active="request()->routeIs('pengajuan.*')">
                            {{ __('Pengajuan Surat Saya') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-green-600 text-sm leading-4 font-medium rounded-md text-white bg-green-700 hover:bg-green-600 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-green-200 hover:text-white hover:bg-green-700 focus:outline-none focus:bg-green-700 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-green-800">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.penduduk.index')" :active="request()->routeIs('admin.penduduk.*')">
                    {{ __('Data Warga') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.kop_surat.edit')" :active="request()->routeIs('admin.kop_surat.*')">
                    {{ __('Kop Surat') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.tanda_tangan.edit')" :active="request()->routeIs('admin.tanda_tangan.*')">
                    {{ __('Tanda Tangan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.jenis_surat.index')" :active="request()->routeIs('admin.jenis_surat.*')">
                    {{ __('Jenis Surat') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.template_surat.index')" :active="request()->routeIs('admin.template_surat.*')">
                    {{ __('Template Surat') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.pengajuan_surat.index')" :active="request()->routeIs('admin.pengajuan_surat.*')">
                    {{ __('Pengajuan Surat') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('Kelola Pengguna') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.*')">
                    {{ __('Laporan') }}
                </x-responsive-nav-link>
            @elseif(Auth::user()->role === 'kepala_desa')
                <x-responsive-nav-link :href="route('kades.dashboard')" :active="request()->routeIs('kades.dashboard')">
                    {{ __('Dashboard Kades') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('kades.persetujuan.index')" :active="request()->routeIs('kades.persetujuan.*')">
                    {{ __('Persetujuan Surat') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('kades.laporan.index')" :active="request()->routeIs('kades.laporan.*')">
                    {{ __('Laporan') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pengajuan.index')" :active="request()->routeIs('pengajuan.*')">
                    {{ __('Pengajuan Surat Saya') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-green-700">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-green-300">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
