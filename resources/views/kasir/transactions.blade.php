<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Riwayat Transaksi</h3>

                        <!-- Filter Section -->
                        <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                <input type="date" class="w-full border border-gray-300 rounded-md px-3 py-2"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                                <input type="date" class="w-full border border-gray-300 rounded-md px-3 py-2"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                                <select class="w-full border border-gray-300 rounded-md px-3 py-2">
                                    <option value="">Semua Metode</option>
                                    <option value="E-Wallet">E-Wallet</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Cash">Cash</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded w-full">
                                    Filter
                                </button>
                            </div>
                        </div>

                        <!-- Summary Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-blue-600">Total Transaksi</h4>
                                <p class="text-2xl font-bold text-blue-900">{{ $transaksiheader->count() }}</p>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-green-600">Total Pendapatan</h4>
                                <p class="text-2xl font-bold text-green-900">Rp
                                    {{ number_format($transaksiheader->sum('Total Bayar'), 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-purple-600">Total Tiket</h4>
                                <p class="text-2xl font-bold text-purple-900">{{ $transaksiheader->sum('Jml Tiket') }}
                                </p>
                            </div>
                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-orange-600">Rata-rata Transaksi</h4>
                                <p class="text-2xl font-bold text-orange-900">
                                    Rp
                                    {{ $transaksiheader->count() > 0 ? number_format($transaksiheader->sum('Total Bayar') / $transaksiheader->count(), 0, ',', '.') : '0' }}
                                </p>
                            </div>
                        </div>

                        <!-- Transactions Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            ID Transaksi</th>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            Waktu</th>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            Pelanggan</th>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            Lokasi</th>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            Jumlah Tiket</th>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            Total Bayar</th>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            Metode</th>
                                        <th
                                            class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($transaksiheader as $transaksi)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                                #{{ str_pad($transaksi->ID, 3, '0', STR_PAD_LEFT) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($transaksi->Waktu)->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $transaksi->Pelanggan }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $transaksi->Lokasi }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 text-center">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $transaksi->{'Jml Tiket'} }} tiket
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                                Rp {{ number_format($transaksi->{'Total Bayar'}, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if ($transaksi->Metode == 'E-Wallet') bg-green-100 text-green-800
                                                @elseif($transaksi->Metode == 'Bank Transfer') bg-blue-100 text-blue-800
                                                @elseif($transaksi->Metode == 'Credit Card') bg-purple-100 text-purple-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $transaksi->Metode }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <div class="flex space-x-2">
                                                    <button onclick="showTransactionDetail({{ $transaksi->ID }})"
                                                        class="text-blue-600 hover:text-blue-900 text-sm font-medium">
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
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                    <p class="text-lg font-medium text-gray-900">Tidak ada transaksi</p>
                                                    <p class="text-gray-500">Belum ada data transaksi yang tersedia</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination (if needed) -->
                        @if ($transaksiheader->count() > 0)
                            <div class="mt-6 flex justify-between items-center">
                                <div class="text-sm text-gray-700">
                                    Menampilkan {{ $transaksiheader->count() }} transaksi
                                </div>
                                <div class="text-sm text-gray-500">
                                    Total: Rp {{ number_format($transaksiheader->sum('Total Bayar'), 0, ',', '.') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Transaction Modal -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Detail Transaksi</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div id="modalContent" class="mt-4">
                    <!-- Content will be loaded here via AJAX -->
                    <div class="flex items-center justify-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                        <span class="ml-3 text-gray-600">Loading...</span>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end pt-4 border-t space-x-2">
                    <button onclick="printTransaction()"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm">
                        Print
                    </button>
                    <button onclick="closeModal()"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        let currentTransactionId = null;

        function showTransactionDetail(transactionId) {
            currentTransactionId = transactionId;
            document.getElementById('detailModal').classList.remove('hidden');

            // Reset modal content
            document.getElementById('modalContent').innerHTML = `
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                    <span class="ml-3 text-gray-600">Loading...</span>
                </div>
            `;

            // Fetch transaction details via AJAX
            fetch(`/kasir/transactions/${transactionId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayTransactionDetail(data.transaction);
                    } else {
                        showError('Gagal memuat detail transaksi');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('Terjadi kesalahan saat memuat data');
                });
        }

        // Update the displayTransactionDetail function in your transactions.blade.php file

        function displayTransactionDetail(transaction) {
            const content = `
        <div class="space-y-6">
            <!-- Transaction Header Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-blue-600">ID Transaksi</label>
                        <p class="text-lg font-bold text-blue-900">#${String(transaction.header.ID).padStart(3, '0')}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-blue-600">Waktu Transaksi</label>
                        <p class="text-lg font-bold text-blue-900">${new Date(transaction.header.Waktu).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })}</p>
                    </div>
                </div>
            </div>

            <!-- Customer and Location Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-600 mb-2">Informasi Pelanggan</h4>
                    <p class="text-lg font-semibold text-gray-900">${transaction.header.Pelanggan}</p>
                </div>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-600 mb-2">Lokasi Bioskop</h4>
                    <p class="text-lg font-semibold text-gray-900">${transaction.header.Cabang || transaction.header.Lokasi}</p>
                </div>
            </div>

            <!-- Transaction Details Table -->
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Detail Tiket</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 border-b text-left text-xs font-medium text-gray-500 uppercase">ID Detail</th>
                                <th class="px-4 py-2 border-b text-left text-xs font-medium text-gray-500 uppercase">Film</th>
                                <th class="px-4 py-2 border-b text-left text-xs font-medium text-gray-500 uppercase">Studio</th>
                                <th class="px-4 py-2 border-b text-left text-xs font-medium text-gray-500 uppercase">No Kursi</th>
                                <th class="px-4 py-2 border-b text-right text-xs font-medium text-gray-500 uppercase">Harga Tiket</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            ${transaction.details.map(detail => `
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">#${String(detail['ID Detail']).padStart(2, '0')}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                <div class="font-medium">${detail.Film || '-'}</div>
                                                <div class="text-xs text-gray-500">ID Transaksi: ${detail.ID}</div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    ${detail.Studio || '-'}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    ${detail['No Kursi'] || '-'}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">
                                                Rp ${parseInt(detail['Harga Tiket'] || 0).toLocaleString('id-ID')}
                                            </td>
                                        </tr>
                                    `).join('')}
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-sm font-medium text-gray-900 text-right">
                                    <strong>Total Tiket:</strong>
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-gray-900 text-right">
                                    ${transaction.details.length} tiket
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-sm font-medium text-gray-900 text-right">
                                    <strong>Subtotal:</strong>
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-gray-900 text-right">
                                    Rp ${transaction.details.reduce((sum, detail) => sum + parseInt(detail['Harga Tiket'] || 0), 0).toLocaleString('id-ID')}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-green-600">Metode Pembayaran</label>
                        <p class="text-lg font-bold text-green-900">${transaction.details[0]?.Pembayaran || transaction.header.Metode || '-'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-green-600">Jumlah Tiket</label>
                        <p class="text-lg font-bold text-green-900">${transaction.details.length} tiket</p>
                    </div>
                    <div class="text-right">
                        <label class="block text-sm font-medium text-green-600">Total Pembayaran</label>
                        <p class="text-2xl font-bold text-green-900">Rp ${parseInt(transaction.header['Total Bayar']).toLocaleString('id-ID')}</p>
                    </div>
                </div>
            </div>

            <!-- Additional Transaction Info -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-600 mb-3">Informasi Tambahan</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Waktu Pembelian:</span>
                        <span class="ml-2 font-medium">${new Date(transaction.header.Waktu).toLocaleDateString('id-ID', {
                            weekday: 'long',
                            day: '2-digit',
                            month: 'long', 
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Status:</span>
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Selesai
                        </span>
                    </div>
                </div>
            </div>
        </div>
    `;

            document.getElementById('modalContent').innerHTML = content;
        }

        function showError(message) {
            document.getElementById('modalContent').innerHTML = `
                <div class="flex flex-col items-center py-8">
                    <svg class="w-12 h-12 text-red-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-lg font-medium text-gray-900">Error</p>
                    <p class="text-gray-500">${message}</p>
                </div>
            `;
        }

        function closeModal() {
            document.getElementById('detailModal').classList.add('hidden');
            currentTransactionId = null;
        }

        function printTransaction() {
            if (currentTransactionId) {
                window.open(`/kasir/transactions/${currentTransactionId}/print`, '_blank');
            }
        }

        // Close modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</x-app-layout>
