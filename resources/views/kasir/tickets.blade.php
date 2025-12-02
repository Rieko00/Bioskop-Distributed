<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penjualan Tiket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Penjualan Tiket Bioskop</h3>

                        <!-- Quick Actions -->
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-6">
                            <div
                                class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex justify-between items-center">
                                <h4 class="font-semibold text-blue-900 mb-2">Tiket Baru</h4>
                                <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                                    Jual Tiket
                                </button>
                            </div>
                        </div>

                        <!-- Today's Sales Summary -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold mb-2">Ringkasan Hari Ini</h4>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-blue-600">{{ $summary['tiket_terjual'] }}</p>
                                    <p class="text-sm text-gray-600">Tiket Terjual</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-green-600">{{ $summary['total_penjualan'] }}</p>
                                    <p class="text-sm text-gray-600">Total Penjualan</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-yellow-600">{{ $summary['film_berbeda'] }}</p>
                                    <p class="text-sm text-gray-600">Film Berbeda</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-purple-600">{{ $summary['transaksi_hari_ini'] }}
                                    </p>
                                    <p class="text-sm text-gray-600">Transaksi Hari Ini</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
