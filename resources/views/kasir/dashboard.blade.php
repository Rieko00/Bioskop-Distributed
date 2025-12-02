<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kasir Dashboard') }}
        </h2>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Selamat datang, {{ Auth::user()->name }}!</h3>
                    <p class="mb-4">Anda login sebagai <span class="font-bold text-green-600">Kasir</span></p>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                        <!-- Kasir Menu Items -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-900 mb-2">Penjualan Tiket</h4>
                            <p class="text-blue-700 text-sm">Proses transaksi penjualan tiket</p>
                        </div>

                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <h4 class="font-semibold text-green-900 mb-2">Jadwal Film</h4>
                            <p class="text-green-700 text-sm">Lihat jadwal film hari ini</p>
                        </div>

                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <h4 class="font-semibold text-purple-900 mb-2">Riwayat Transaksi</h4>
                            <p class="text-purple-700 text-sm">Cek transaksi yang sudah dilakukan</p>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h4 class="font-semibold text-yellow-900 mb-2">Data Pelanggan</h4>
                            <p class="text-yellow-700 text-sm">Kelola data pelanggan</p>
                        </div>

                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <h4 class="font-semibold text-red-900 mb-2">Laporan Harian</h4>
                            <p class="text-red-700 text-sm">Laporan penjualan harian</p>
                        </div>

                        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                            <h4 class="font-semibold text-indigo-900 mb-2">Seat Booking</h4>
                            <p class="text-indigo-700 text-sm">Kelola reservasi kursi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
