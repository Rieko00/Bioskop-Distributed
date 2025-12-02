<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jadwal Film') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Jadwal Tayang Hari Ini</h3>

                        <!-- Date Selector -->
                        <div class="mb-6 flex items-center gap-4">
                            <label class="font-medium">Pilih Tanggal:</label>
                            <input type="date" class="border border-gray-300 rounded-md px-3 py-2"
                                value="{{ date('Y-m-d') }}">
                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                Filter
                            </button>
                        </div>

                        <!-- Summary Info -->
                        @if (isset($jadwals) && $jadwals->count() > 0)
                            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-blue-600">Total Jadwal</h4>
                                    <p class="text-2xl font-bold text-blue-900">{{ $jadwals->count() }}</p>
                                </div>
                                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-purple-600">Film Berbeda</h4>
                                    <p class="text-2xl font-bold text-purple-900">
                                        {{ $jadwals->unique('Judul Film')->count() }}</p>
                                </div>
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-green-600">Lokasi Bioskop</h4>
                                    <p class="text-2xl font-bold text-green-900">
                                        {{ $jadwals->unique('Lokasi Bioskop')->count() }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Schedule Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            Film</th>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            Studio</th>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            Lokasi</th>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            Jam Tayang</th>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            Durasi</th>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            Harga</th>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            Rating</th>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($jadwals as $jadwal)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-gray-900">{{ $jadwal->{'Judul Film'} }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    Durasi: {{ $jadwal->{'Durasi (Menit)'} }} menit
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $jadwal->Studio }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $jadwal->{'Lokasi Bioskop'} }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                                {{ \Carbon\Carbon::parse($jadwal->{'Jam Tayang'})->format('H:i') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $jadwal->{'Durasi (Menit)'} }} min
                                            </td>
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                                Rp {{ number_format($jadwal->{'Harga Tiket'}, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if ($jadwal->Rating == 'G') bg-green-100 text-green-800
                                                @elseif($jadwal->Rating == 'PG') bg-yellow-100 text-yellow-800
                                                @elseif($jadwal->Rating == 'PG-13') bg-orange-100 text-orange-800
                                                @elseif($jadwal->Rating == 'R') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $jadwal->Rating }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <div class="flex space-x-2">
                                                    <button
                                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs font-medium">
                                                        Jual Tiket
                                                    </button>
                                                    <button
                                                        class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-xs font-medium">
                                                        Detail
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <p class="text-lg font-medium text-gray-900">Tidak ada jadwal</p>
                                                    <p class="text-gray-500">Belum ada jadwal film untuk hari ini</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Footer -->
                        @if (isset($jadwals) && $jadwals->count() > 0)
                            <div class="mt-6 flex justify-between items-center">
                                <div class="text-sm text-gray-700">
                                    Menampilkan {{ $jadwals->count() }} jadwal tayang
                                </div>
                                <div class="text-sm text-gray-500">
                                    Harga mulai dari: Rp {{ number_format($jadwals->min('Harga Tiket'), 0, ',', '.') }}
                                </div>
                            </div>
                        @endif

                        <!-- Filter by Location -->
                        @if (isset($jadwals) && $jadwals->count() > 0)
                            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-2">Filter berdasarkan Lokasi:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($jadwals->unique('Lokasi Bioskop') as $lokasi)
                                        <button
                                            class="px-3 py-1 bg-white border border-gray-300 rounded-full text-sm hover:bg-blue-50 hover:border-blue-300">
                                            {{ $lokasi->{'Lokasi Bioskop'} }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
