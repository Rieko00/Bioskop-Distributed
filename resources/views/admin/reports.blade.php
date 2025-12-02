<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan System') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Laporan Pendapatan Per Film -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Laporan Pendapatan per Film</h3>
                        <p class="text-gray-600 text-sm mb-4">Laporan detail pendapatan dan penjualan tiket untuk setiap
                            film</p>
                        <button onclick="generateFilmReport()"
                            class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Generate Laporan
                        </button>
                    </div>
                </div>

                <!-- Laporan Jadwal Lengkap -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Laporan Jadwal Lengkap</h3>
                        <p class="text-gray-600 text-sm mb-4">Jadwal tayang lengkap dengan detail film, studio, dan
                            cabang</p>
                        <div class="mb-3">
                            <input type="text" id="cityCode" placeholder="Kode Kota (opsional)"
                                class="w-full px-3 py-2 border border-gray-300 rounded">
                        </div>
                        <button onclick="generateScheduleReport()"
                            class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Generate Laporan
                        </button>
                    </div>
                </div>

                <!-- Summary Dashboard -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Summary System</h3>
                        <p class="text-gray-600 text-sm mb-4">Ringkasan data sistem secara keseluruhan</p>
                        <button onclick="viewSystemSummary()"
                            class="w-full bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                            Lihat Summary
                        </button>
                    </div>
                </div>

                <!-- Laporan per Cabang -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Laporan per Cabang</h3>
                        <p class="text-gray-600 text-sm mb-4">Performance analysis per cabang bioskop</p>
                        <button onclick="generateBranchReport()"
                            class="w-full bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Generate Laporan
                        </button>
                    </div>
                </div>

                <!-- Laporan Users -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Laporan Users</h3>
                        <p class="text-gray-600 text-sm mb-4">Daftar dan aktivitas pengguna sistem</p>
                        <button onclick="generateUserReport()"
                            class="w-full bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Generate Laporan
                        </button>
                    </div>
                </div>

                <!-- Export Data -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Export Data</h3>
                        <p class="text-gray-600 text-sm mb-4">Export data sistem ke berbagai format</p>
                        <div class="space-y-2">
                            <button onclick="exportData('csv')"
                                class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-2 rounded text-sm">
                                Export CSV
                            </button>
                            <button onclick="exportData('excel')"
                                class="w-full bg-gray-600 hover:bg-gray-800 text-white font-bold py-1 px-2 rounded text-sm">
                                Export Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Results -->
            <div id="reportResults" class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Hasil Laporan</h3>
                        <button onclick="closeReportResults()" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="reportContent">
                        <!-- Report content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function generateFilmReport() {
            showLoading();

            // Simulate stored procedure call
            fetch('/admin/reports/film-revenue', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        displayReportResults('Laporan Pendapatan per Film', data.data, [
                            'Film', 'Total Tiket Terjual', 'Total Pendapatan (Rp)'
                        ]);
                    } else {
                        alert('Error generating film report: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    // For demo purposes, show sample data
                    const sampleData = [
                        ['Spider-Man: No Way Home', '1250', '87,500,000'],
                        ['Doctor Strange 2', '980', '68,600,000'],
                        ['Top Gun: Maverick', '850', '59,500,000'],
                        ['Avatar: The Way of Water', '720', '50,400,000']
                    ];
                    displayReportResults('Laporan Pendapatan per Film', sampleData, [
                        'Film', 'Total Tiket Terjual', 'Total Pendapatan (Rp)'
                    ]);
                });
        }

        function generateScheduleReport() {
            const cityCode = document.getElementById('cityCode').value;
            showLoading();

            // Simulate stored procedure call
            setTimeout(() => {
                hideLoading();
                const sampleData = [
                    ['Spider-Man: No Way Home', 'PG-13', '148 menit', 'Studio 1', 'Bioskop Central Jakarta',
                        '14:00', '50,000'
                    ],
                    ['Doctor Strange 2', 'PG-13', '126 menit', 'Studio 2', 'Bioskop Central Jakarta', '16:30',
                        '55,000'
                    ],
                    ['Top Gun: Maverick', 'PG-13', '130 menit', 'Studio 3', 'Bioskop Mall Kelapa Gading',
                        '19:00', '60,000'
                    ]
                ];
                displayReportResults('Laporan Jadwal Lengkap' + (cityCode ? ` - ${cityCode}` : ''), sampleData, [
                    'Judul Film', 'Rating', 'Durasi', 'Studio', 'Lokasi Bioskop', 'Jam Tayang',
                    'Harga Tiket'
                ]);
            }, 1000);
        }

        function viewSystemSummary() {
            showLoading();

            setTimeout(() => {
                hideLoading();
                const summaryData = [
                    ['Total Cabang', '{{ $stats->total_cabang ?? '5' }}'],
                    ['Total Studio', '{{ $stats->total_studio ?? '25' }}'],
                    ['Total Film Aktif', '{{ $stats->total_film ?? '15' }}'],
                    ['Total Admin', '{{ $stats->total_admin ?? '3' }}'],
                    ['Total Kasir', '{{ $stats->total_kasir ?? '12' }}'],
                    ['Transaksi Hari Ini', '{{ $stats->transaksi_hari_ini ?? '45' }}'],
                    ['Pendapatan Hari Ini',
                        'Rp {{ number_format($stats->pendapatan_hari_ini ?? 2750000, 0, ',', '.') }}'
                    ]
                ];
                displayReportResults('Summary System', summaryData, [
                    'Metrik', 'Nilai'
                ]);
            }, 500);
        }

        function generateBranchReport() {
            showLoading();

            setTimeout(() => {
                hideLoading();
                const branchData = [
                    ['Central Jakarta', '5', '125', 'Rp 15,750,000'],
                    ['Mall Kelapa Gading', '4', '98', 'Rp 12,250,000'],
                    ['Pondok Indah', '3', '78', 'Rp 9,750,000'],
                    ['BSD City', '4', '89', 'Rp 11,125,000'],
                    ['Cibubur', '3', '67', 'Rp 8,375,000']
                ];
                displayReportResults('Laporan Performance per Cabang', branchData, [
                    'Nama Cabang', 'Jumlah Studio', 'Transaksi Bulanan', 'Revenue Bulanan'
                ]);
            }, 800);
        }

        function generateUserReport() {
            showLoading();

            setTimeout(() => {
                hideLoading();
                const userData = [
                    ['Admin Pusat', 'admin', 'admin@bioskop.com', 'Aktif', '2024-01-15'],
                    ['John Doe', 'kasir', 'john@bioskop.com', 'Aktif', '2024-02-20'],
                    ['Jane Smith', 'kasir', 'jane@bioskop.com', 'Aktif', '2024-03-10'],
                    ['Bob Wilson', 'kasir', 'bob@bioskop.com', 'Aktif', '2024-03-25']
                ];
                displayReportResults('Laporan Users System', userData, [
                    'Nama', 'Role', 'Email', 'Status', 'Bergabung'
                ]);
            }, 600);
        }

        function exportData(format) {
            alert(`Fungsi export ${format.toUpperCase()} akan segera tersedia`);
        }

        function displayReportResults(title, data, headers) {
            const resultsDiv = document.getElementById('reportResults');
            const contentDiv = document.getElementById('reportContent');

            let tableHtml = `
                <h4 class="text-lg font-semibold mb-4">${title}</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                ${headers.map(header => `<th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">${header}</th>`).join('')}
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${data.map(row => `
                                    <tr>
                                        ${row.map(cell => `<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${cell}</td>`).join('')}
                                    </tr>
                                `).join('')}
                        </tbody>
                    </table>
                </div>
            `;

            contentDiv.innerHTML = tableHtml;
            resultsDiv.classList.remove('hidden');
            resultsDiv.scrollIntoView({
                behavior: 'smooth'
            });
        }

        function closeReportResults() {
            document.getElementById('reportResults').classList.add('hidden');
        }

        function showLoading() {
            // Show loading indicator
            const buttons = document.querySelectorAll('button[onclick^="generate"], button[onclick="viewSystemSummary"]');
            buttons.forEach(btn => {
                btn.disabled = true;
                btn.innerHTML = btn.innerHTML.replace('Generate', 'Loading').replace('Lihat', 'Loading');
            });
        }

        function hideLoading() {
            // Hide loading indicator
            const buttons = document.querySelectorAll('button[onclick^="generate"], button[onclick="viewSystemSummary"]');
            buttons.forEach(btn => {
                btn.disabled = false;
                btn.innerHTML = btn.innerHTML.replace('Loading', 'Generate').replace('Loading', 'Lihat');
            });
        }
    </script>
</x-app-layout>
