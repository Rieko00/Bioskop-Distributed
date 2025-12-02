<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard - Pusat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100">Total Cabang</p>
                            <p class="text-3xl font-bold">{{ $stats->total_cabang ?? 0 }}</p>
                        </div>
                        <div class="text-4xl opacity-80">
                            🏢
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100">Total Studio</p>
                            <p class="text-3xl font-bold">{{ $stats->total_studio ?? 0 }}</p>
                        </div>
                        <div class="text-4xl opacity-80">
                            🎭
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100">Total Film</p>
                            <p class="text-3xl font-bold">{{ $stats->total_film ?? 0 }}</p>
                        </div>
                        <div class="text-4xl opacity-80">
                            🎬
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100">Jadwal Aktif</p>
                            <p class="text-3xl font-bold">{{ $stats->total_jadwal ?? 0 }}</p>
                        </div>
                        <div class="text-4xl opacity-80">
                            📅
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600">Total Administrator</p>
                            <p class="text-2xl font-bold text-indigo-600">{{ $stats->total_admin ?? 0 }}</p>
                        </div>
                        <div class="text-3xl">👨‍💼</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600">Total Kasir</p>
                            <p class="text-2xl font-bold text-pink-600">{{ $stats->total_kasir ?? 0 }}</p>
                        </div>
                        <div class="text-3xl">👩‍💻</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600">Transaksi Hari Ini</p>
                            <p class="text-2xl font-bold text-emerald-600">{{ $stats->transaksi_hari_ini ?? 0 }}</p>
                        </div>
                        <div class="text-3xl">💳</div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Selamat datang, {{ Auth::user()->name }}!</h3>
                    <p class="mb-6">Anda login sebagai <span class="font-bold text-red-600">Administrator Pusat</span>
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                        <!-- User Management -->
                        <a href="{{ route('admin.users') }}" class="block">
                            <div
                                class="bg-blue-50 border border-blue-200 rounded-lg p-6 hover:bg-blue-100 transition-colors duration-200">
                                <div class="flex items-center mb-3">
                                    <div class="text-3xl mr-3">👥</div>
                                    <h4 class="font-semibold text-blue-900">Manajemen Pengguna</h4>
                                </div>
                                <p class="text-blue-700 text-sm">Kelola akun admin dan kasir</p>
                                <div class="mt-3 text-blue-600 text-sm font-medium">
                                    {{ ($stats->total_admin ?? 0) + ($stats->total_kasir ?? 0) }} total users →
                                </div>
                            </div>
                        </a>

                        <!-- Branch Management -->
                        <a href="{{ route('admin.branches') }}" class="block">
                            <div
                                class="bg-green-50 border border-green-200 rounded-lg p-6 hover:bg-green-100 transition-colors duration-200">
                                <div class="flex items-center mb-3">
                                    <div class="text-3xl mr-3">🏢</div>
                                    <h4 class="font-semibold text-green-900">Manajemen Cabang</h4>
                                </div>
                                <p class="text-green-700 text-sm">Kelola data cabang bioskop</p>
                                <div class="mt-3 text-green-600 text-sm font-medium">
                                    {{ $stats->total_cabang ?? 0 }} cabang →
                                </div>
                            </div>
                        </a>

                        <!-- Studio Management -->
                        <a href="{{ route('admin.studios') }}" class="block">
                            <div
                                class="bg-indigo-50 border border-indigo-200 rounded-lg p-6 hover:bg-indigo-100 transition-colors duration-200">
                                <div class="flex items-center mb-3">
                                    <div class="text-3xl mr-3">🎭</div>
                                    <h4 class="font-semibold text-indigo-900">Manajemen Studio</h4>
                                </div>
                                <p class="text-indigo-700 text-sm">Kelola studio di semua cabang</p>
                                <div class="mt-3 text-indigo-600 text-sm font-medium">
                                    {{ $stats->total_studio ?? 0 }} studio →
                                </div>
                            </div>
                        </a>

                        <!-- Film Management -->
                        <a href="{{ route('admin.films') }}" class="block">
                            <div
                                class="bg-purple-50 border border-purple-200 rounded-lg p-6 hover:bg-purple-100 transition-colors duration-200">
                                <div class="flex items-center mb-3">
                                    <div class="text-3xl mr-3">🎬</div>
                                    <h4 class="font-semibold text-purple-900">Manajemen Film</h4>
                                </div>
                                <p class="text-purple-700 text-sm">Kelola data film dan konten</p>
                                <div class="mt-3 text-purple-600 text-sm font-medium">
                                    {{ $stats->total_film ?? 0 }} film →
                                </div>
                            </div>
                        </a>

                        <!-- Schedule Management -->
                        <a href="{{ route('admin.schedules') }}" class="block">
                            <div
                                class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 hover:bg-yellow-100 transition-colors duration-200">
                                <div class="flex items-center mb-3">
                                    <div class="text-3xl mr-3">📅</div>
                                    <h4 class="font-semibold text-yellow-900">Jadwal Tayang</h4>
                                </div>
                                <p class="text-yellow-700 text-sm">Kelola jadwal tayang film</p>
                                <div class="mt-3 text-yellow-600 text-sm font-medium">
                                    {{ $stats->total_jadwal ?? 0 }} jadwal →
                                </div>
                            </div>
                        </a>

                        <!-- Reports -->
                        <a href="{{ route('admin.reports') }}" class="block">
                            <div
                                class="bg-pink-50 border border-pink-200 rounded-lg p-6 hover:bg-pink-100 transition-colors duration-200">
                                <div class="flex items-center mb-3">
                                    <div class="text-3xl mr-3">📊</div>
                                    <h4 class="font-semibold text-pink-900">Laporan System</h4>
                                </div>
                                <p class="text-pink-700 text-sm">Laporan komprehensif sistem</p>
                                <div class="mt-3 text-pink-600 text-sm font-medium">
                                    View reports →
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Today's Summary -->
                    <div class="mt-8 bg-gray-50 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 mb-4">Ringkasan Hari Ini</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Total Transaksi:</span>
                                <span class="font-semibold text-gray-900">{{ $stats->transaksi_hari_ini ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Total Pendapatan:</span>
                                <span class="font-semibold text-gray-900">Rp
                                    {{ number_format($stats->pendapatan_hari_ini ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
