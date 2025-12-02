<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Pelanggan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Daftar Pelanggan</h3>
                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                Tambah Pelanggan
                            </button>
                        </div>

                        <!-- Search and Filter -->
                        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Pelanggan</label>
                                <input type="text" placeholder="Nama, email, atau nomor HP..."
                                    class="w-full border border-gray-300 rounded-md px-3 py-2">
                            </div>
                            <div class="flex items-end">
                                <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded w-full">
                                    Cari
                                </button>
                            </div>
                        </div>

                        <!-- Customer Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-blue-600">Total Pelanggan</h4>
                                <p class="text-2xl font-bold text-blue-900">{{ $length }}</p>
                            </div>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-yellow-600">Pelanggan Baru (Bulan Ini)</h4>
                                <p class="text-2xl font-bold text-yellow-900">{{ $pelanggan_bulan_ini }}</p>
                            </div>
                        </div>

                        <!-- Customers Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            ID</th>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            Nama</th>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            Email</th>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            No. HP</th>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            Terdaftar</th>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($customers as $customer)
                                        <tr>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                                #C{{ str_pad($customer->id_pelanggan, 3, '0', STR_PAD_LEFT) }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $customer->nama }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $customer->email }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $customer->telp }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $customer->Bergabung_Sejak }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <a href=""
                                                    class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>
                                                <a href=""
                                                    class="text-green-600 hover:text-green-900 mr-2">History</a>
                                                <form action="" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Hapus pelanggan ini?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Tidak
                                                ada data.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6 flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">4</span>
                                dari <span class="font-medium">1,247</span> hasil
                            </div>
                            <div class="flex space-x-2">
                                <button
                                    class="px-3 py-2 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 disabled:opacity-50"
                                    disabled>
                                    Sebelumnya
                                </button>
                                <button class="px-3 py-2 text-sm bg-blue-500 text-white rounded hover:bg-blue-600">
                                    1
                                </button>
                                <button class="px-3 py-2 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                                    2
                                </button>
                                <button class="px-3 py-2 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                                    3
                                </button>
                                <button class="px-3 py-2 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                                    Selanjutnya
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
