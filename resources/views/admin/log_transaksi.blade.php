<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Log Transaksi Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filters and Actions -->
                    <div class="mb-6 space-y-4">
                        <h3 class="text-lg font-semibold">Filter Log Transaksi</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                <input type="date" id="startDate"
                                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                                <input type="date" id="endDate"
                                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cabang</label>
                                <select id="cabangFilter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                                    <option value="">Semua Cabang</option>
                                    @foreach ($cabangs as $cabang)
                                        <option value="{{ $cabang->id_cabang }}">{{ $cabang->nama_cabang }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Film</label>
                                <select id="filmFilter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                                    <option value="">Semua Film</option>
                                    @foreach ($films as $film)
                                        <option value="{{ $film->id_film }}">{{ $film->judul }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <button id="applyFilter"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Terapkan Filter
                            </button>
                            <button id="resetFilter"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Reset Filter
                            </button>
                            <button id="exportExcel"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Export Excel
                            </button>
                            <button id="exportPdf"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Export PDF
                            </button>
                        </div>
                    </div>

                    <!-- Log Transaction Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200" id="logTransaksiTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No Transaksi</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cabang</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pelanggan</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Film</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Studio</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kursi</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Bayar</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Waktu Transaksi</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="logTransaksiTableBody">
                                @forelse($logTransaksis as $log)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $log->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            {{ $log->id_transaksi ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $log->nama_cabang ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $log->nama_pelanggan ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $log->judul_film ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $log->nama_studio ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $log->seat_code ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">
                                            Rp {{ number_format($log->harga ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->waktu_transaksi ? \Carbon\Carbon::parse($log->waktu_transaksi)->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="viewDetail('{{ $log->rowguid }}')"
                                                class="text-blue-600 hover:text-blue-900">
                                                View Detail
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-6 py-4 text-center text-gray-500">Belum ada data
                                            log transaksi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border max-w-4xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900" id="detailModalTitle">Detail Log Transaksi</h3>
                    <button id="closeDetailModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div id="detailContent" class="space-y-4">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize
        document.getElementById('applyFilter').addEventListener('click', applyFilter);
        document.getElementById('resetFilter').addEventListener('click', resetFilter);
        document.getElementById('exportExcel').addEventListener('click', exportExcel);
        document.getElementById('exportPdf').addEventListener('click', exportPdf);
        document.getElementById('closeDetailModal').addEventListener('click', closeDetailModal);

        function applyFilter() {
            const filters = {
                start_date: document.getElementById('startDate').value,
                end_date: document.getElementById('endDate').value,
                id_cabang: document.getElementById('cabangFilter').value,
                id_film: document.getElementById('filmFilter').value
            };

            fetch('/admin/reports/log-transaksi/filter', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(filters)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateTable(data.data);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menerapkan filter');
                });
        }

        function resetFilter() {
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
            document.getElementById('cabangFilter').value = '';
            document.getElementById('filmFilter').value = '';
            location.reload();
        }

        function exportExcel() {
            const filters = getFilters();
            const url = new URL('/admin/reports/log-transaksi/export/excel', window.location.origin);
            Object.keys(filters).forEach(key => {
                if (filters[key]) url.searchParams.append(key, filters[key]);
            });
            window.open(url, '_blank');
        }

        function exportPdf() {
            const filters = getFilters();
            const url = new URL('/admin/reports/log-transaksi/export/pdf', window.location.origin);
            Object.keys(filters).forEach(key => {
                if (filters[key]) url.searchParams.append(key, filters[key]);
            });
            window.open(url, '_blank');
        }

        function getFilters() {
            return {
                start_date: document.getElementById('startDate').value,
                end_date: document.getElementById('endDate').value,
                id_cabang: document.getElementById('cabangFilter').value,
                id_film: document.getElementById('filmFilter').value
            };
        }

        function updateTable(data) {
            const tbody = document.getElementById('logTransaksiTableBody');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML =
                    '<tr><td colspan="10" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td></tr>';
                return;
            }

            data.forEach(log => {
                const row = `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">${log.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">${log.id_transaksi || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">${log.nama_cabang || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">${log.nama_pelanggan || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">${log.judul_film || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">${log.nama_studio || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">${log.seat_code || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">
                            Rp ${new Intl.NumberFormat('id-ID').format(log.harga || 0)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${log.waktu_transaksi ? new Date(log.waktu_transaksi).toLocaleDateString('id-ID', { 
                                year: 'numeric', month: '2-digit', day: '2-digit',
                                hour: '2-digit', minute: '2-digit'
                            }) : '-'}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="viewDetail('${log.rowguid}')" class="text-blue-600 hover:text-blue-900">
                                View Detail
                            </button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        function viewDetail(rowguid) {
            fetch(`/admin/reports/log-transaksi/${encodeURIComponent(rowguid)}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showDetailModal(data.data);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat detail');
                });
        }

        function showDetailModal(data) {
            const content = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <h4 class="font-semibold text-lg border-b pb-2">Informasi Transaksi</h4>
                        <div><strong>ID Log:</strong> ${data.id}</div>
                        <div><strong>ID Transaksi:</strong> ${data.id_transaksi || '-'}</div>
                        <div><strong>Total Bayar:</strong> Rp ${new Intl.NumberFormat('id-ID').format(data.total_bayar || 0)}</div>
                        <div><strong>Harga:</strong> Rp ${new Intl.NumberFormat('id-ID').format(data.harga || 0)}</div>
                        <div><strong>Metode Pembayaran:</strong> ${data.metode_pembayaran || '-'}</div>
                        <div><strong>Waktu Transaksi:</strong> ${data.waktu_transaksi ? new Date(data.waktu_transaksi).toLocaleDateString('id-ID', { 
                            year: 'numeric', month: '2-digit', day: '2-digit',
                            hour: '2-digit', minute: '2-digit'
                        }) : '-'}</div>
                    </div>
                    <div class="space-y-3">
                        <h4 class="font-semibold text-lg border-b pb-2">Informasi Film & Studio</h4>
                        <div><strong>Cabang:</strong> ${data.nama_cabang || '-'}</div>
                        <div><strong>Film:</strong> ${data.judul_film || '-'}</div>
                        <div><strong>Studio:</strong> ${data.nama_studio || '-'}</div>
                        <div><strong>Kursi:</strong> ${data.seat_code || '-'}</div>
                        <div><strong>Tanggal Tayang:</strong> ${data.tanggal_tayang || '-'}</div>
                        <div><strong>Waktu Mulai:</strong> ${data.waktu_mulai || '-'}</div>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t">
                    <h4 class="font-semibold text-lg mb-3">Informasi Pelanggan</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div><strong>Nama:</strong> ${data.nama_pelanggan || '-'}</div>
                        <div><strong>Email:</strong> ${data.email_pelanggan || '-'}</div>
                        <div><strong>Telepon:</strong> ${data.no_telepon || '-'}</div>
                    </div>
                </div>
            `;

            document.getElementById('detailContent').innerHTML = content;
            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
