<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penjualan Tiket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Display Error if exists -->
            @if (isset($error))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="text-red-800">{{ $error }}</div>
                </div>
            @endif

            <!-- Summary Cards -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Ringkasan Hari Ini</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Tiket Terjual -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                        <div class="p-6">
                            <div class="text-3xl font-bold text-blue-600">{{ $summary['tiket_terjual'] }}</div>
                            <div class="text-sm font-medium text-gray-600 mt-1">Tiket Terjual</div>
                        </div>
                    </div>

                    <!-- Total Penjualan -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                        <div class="p-6">
                            <div class="text-3xl font-bold text-green-600">Rp
                                {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</div>
                            <div class="text-sm font-medium text-gray-600 mt-1">Total Penjualan</div>
                        </div>
                    </div>

                    <!-- Film Berbeda -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-purple-500">
                        <div class="p-6">
                            <div class="text-3xl font-bold text-purple-600">{{ $summary['film_berbeda'] }}</div>
                            <div class="text-sm font-medium text-gray-600 mt-1">Film Berbeda</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1   gap-6 mb-8">
                <!-- Jual Tiket -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                                🎫
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900">Jual Tiket</h4>
                                <p class="text-sm text-gray-600">Proses penjualan tiket baru</p>
                            </div>
                        </div>
                        <button onclick="openNewTicketModal()"
                            class="mt-4 w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            Mulai Penjualan
                        </button>
                    </div>
                </div>

            </div>

            <!-- Recent Transactions & Popular Films -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Transactions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Transaksi Terbaru</h4>
                        @if (isset($recentTransactions) && $recentTransactions->count() > 0)
                            <div class="space-y-3">
                                @foreach ($recentTransactions as $transaction)
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <div>
                                            <div class="font-medium text-gray-900">
                                                #{{ str_pad($transaction->ID, 3, '0', STR_PAD_LEFT) }}</div>
                                            <div class="text-sm text-gray-500">{{ $transaction->Pelanggan }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">Rp
                                                {{ number_format($transaction->{'Total Bayar'}, 0, ',', '.') }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($transaction->Waktu)->format('H:i') }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-gray-500 py-4">
                                Belum ada transaksi hari ini
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Popular Films -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Film Terpopuler Hari Ini</h4>
                        @if (isset($popularFilms) && $popularFilms->count() > 0)
                            <div class="space-y-3">
                                @foreach ($popularFilms as $film)
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $film['film'] }}</div>
                                            <div class="text-sm text-gray-500">{{ $film['tickets_sold'] }} tiket
                                                terjual</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-green-600">Rp
                                                {{ number_format($film['revenue'], 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-gray-500 py-4">
                                Belum ada data film hari ini
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Ticket Modal -->
    <div id="newTicketModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Transaksi Baru</h3>
                    <button onclick="closeNewTicketModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="newTicketForm" class="mt-4 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Customer Information -->
                        <div class="space-y-4">
                            <h4 class="font-medium text-gray-900">Informasi Pelanggan</h4>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                                <input type="text" id="customer_name" name="customer_name" required minlength="2"
                                    maxlength="255" placeholder="Masukkan nama lengkap"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email (Opsional)</label>
                                <input type="email" id="customer_email" name="customer_email" maxlength="255"
                                    placeholder="contoh@email.com"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                                <input type="tel" id="customer_phone" name="customer_phone" required
                                    pattern="[0-9+\-\s]+" minlength="8" maxlength="20" placeholder="08123456789"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Movie Selection -->
                        <div class="space-y-4">
                            <h4 class="font-medium text-gray-900">Pilih Film & Jadwal</h4>

                            <!-- Branch Selection (in the modal) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Cabang</label>
                                <select id="branch_select" name="branch_id" required
                                    onchange="loadSchedulesByBranch()"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih Cabang Bioskop</option>
                                    @if (isset($branches))
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id_cabang }}">
                                                {{ $branch->id_cabang }} | {{ $branch->nama_cabang }} |
                                                {{ $branch->kode_cabang_kota }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Film & Jadwal</label>
                                <select id="movie_schedule" name="movie_schedule" required
                                    onchange="updateScheduleInfo()"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                                    disabled>
                                    <option value="">Pilih cabang terlebih dahulu</option>
                                </select>
                            </div>

                            <div id="schedule_info" class="bg-blue-50 border border-blue-200 rounded-lg p-4 hidden">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-blue-800">Studio:</span>
                                        <span id="selected_studio" class="text-blue-600"></span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-blue-800">Durasi:</span>
                                        <span id="selected_duration" class="text-blue-600"></span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-blue-800">Jam:</span>
                                        <span id="selected_time" class="text-blue-600"></span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-blue-800">Rating:</span>
                                        <span id="selected_rating" class="text-blue-600"></span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-blue-800">Harga:</span>
                                        <span id="selected_price" class="text-blue-600"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Seat Selection -->
                    <div>
                        <h4 class="font-medium text-gray-900 mb-4">Pilih Kursi</h4>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                            <!-- Ticket Count Selection -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Tiket</label>
                                <select id="ticket_count" name="ticket_count" onchange="updateTicketCount()"
                                    class="border border-gray-300 rounded-md px-3 py-2">
                                    <option value="1">1 Tiket</option>
                                    <option value="2">2 Tiket</option>
                                    <option value="3">3 Tiket</option>
                                    <option value="4">4 Tiket</option>
                                    <option value="5">5 Tiket</option>
                                </select>
                            </div>

                            <!-- Seat Map Legends -->
                            <div class="mb-4 flex flex-wrap gap-4 text-xs">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 bg-green-200 border border-green-400 rounded mr-2"></div>
                                    <span>Tersedia</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-6 h-6 bg-red-200 border border-red-400 rounded mr-2"></div>
                                    <span>Sudah Dipesan</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-6 h-6 bg-blue-200 border border-blue-400 rounded mr-2"></div>
                                    <span>Dipilih</span>
                                </div>
                            </div>

                            <!-- Screen Indicator -->
                            <div class="mb-6 text-center">
                                <div class="bg-gray-800 text-white py-2 px-4 rounded-lg inline-block">
                                    🎬 LAYAR
                                </div>
                            </div>

                            <!-- Seat Map Container -->
                            <div id="seat_map_container" class="bg-white border border-gray-300 rounded-lg p-4 mb-4">
                                <div id="seat_map" class="grid gap-1 justify-center">
                                    <div class="text-center text-gray-500 py-8">
                                        Pilih jadwal untuk melihat peta kursi
                                    </div>
                                </div>
                            </div>

                            <!-- Selected Seats Display -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-sm font-medium text-blue-800">Kursi Dipilih:</span>
                                        <span id="selected_seats_display"
                                            class="text-sm font-bold text-blue-900 ml-2">-</span>
                                    </div>
                                    <button type="button" onclick="clearSelectedSeats()"
                                        class="text-xs text-blue-600 hover:text-blue-800">
                                        Hapus Semua
                                    </button>
                                </div>
                            </div>

                            <!-- Hidden input for selected seats -->
                            <input type="hidden" id="selected_seats_input" name="seats" value="">
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <h4 class="font-medium text-gray-900 mb-4">Metode Pembayaran</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <label
                                class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="Cash" checked class="mr-2">
                                <span class="text-sm font-medium">Cash</span>
                            </label>
                            <label
                                class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="Credit Card" class="mr-2">
                                <span class="text-sm font-medium">Credit Card</span>
                            </label>
                            <label
                                class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="E-Wallet" class="mr-2">
                                <span class="text-sm font-medium">E-Wallet</span>
                            </label>
                            <label
                                class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="Bank Transfer" class="mr-2">
                                <span class="text-sm font-medium">Bank Transfer</span>
                            </label>
                        </div>
                    </div>

                    <!-- Total Summary -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-sm font-medium text-green-800">Total Tiket:</span>
                                <span id="total_tickets" class="text-lg font-bold text-green-900 ml-2">0</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-green-800">Total Bayar:</span>
                                <span id="total_amount" class="text-xl font-bold text-green-900 ml-2">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Modal Footer -->
                <div class="flex justify-end pt-6 border-t space-x-3">
                    <button onclick="closeNewTicketModal()" type="button"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded transition-colors duration-200">
                        Batal
                    </button>
                    <button onclick="processNewTicket()" type="button"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        Proses Transaksi
                    </button>
                </div>

                <!-- Progress Steps -->
                <div id="transaction_progress" class="hidden mt-4 p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-blue-800 font-medium">Memproses transaksi...</span>
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce"
                                style="animation-delay: 0.1s"></div>
                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce"
                                style="animation-delay: 0.2s"></div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="bg-blue-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-1000" style="width: 0%"
                                id="progress_bar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedSchedule = null;
        let selectedSeats = [];
        let maxTickets = 1;
        let bookedSeats = [];
        let currentStudioId = null;

        function openNewTicketModal() {
            document.getElementById('newTicketModal').classList.remove('hidden');
        }

        function closeNewTicketModal() {
            document.getElementById('newTicketModal').classList.add('hidden');
            document.getElementById('newTicketForm').reset();
            selectedSchedule = null;
            selectedSeats = [];
            currentStudioId = null;
            clearSeatMap();
            // Reset branch and schedule selects
            document.getElementById('movie_schedule').disabled = true;
            document.getElementById('movie_schedule').innerHTML = '<option value="">Pilih cabang terlebih dahulu</option>';
        }

        async function loadSchedulesByBranch() {
            const branchSelect = document.getElementById('branch_select');
            const scheduleSelect = document.getElementById('movie_schedule');

            if (!branchSelect.value) {
                scheduleSelect.disabled = true;
                scheduleSelect.innerHTML = '<option value="">Pilih cabang terlebih dahulu</option>';
                clearSeatMap();
                return;
            }

            try {
                // Show loading state
                scheduleSelect.disabled = true;
                scheduleSelect.innerHTML = '<option value="">Loading...</option>';

                const response = await fetch(`/kasir/schedules/branch/${branchSelect.value}`);

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const data = await response.json();

                if (data.success && data.schedules.length > 0) {
                    scheduleSelect.innerHTML = '<option value="">Pilih Film & Jadwal</option>';

                    data.schedules.forEach(schedule => {
                        const option = document.createElement('option');
                        option.value = JSON.stringify(schedule);

                        // Format time from waktu_mulai
                        let timeFormatted = 'N/A';
                        if (schedule.waktu_mulai) {
                            try {
                                timeFormatted = new Date('2000-01-01T' + schedule.waktu_mulai)
                                    .toLocaleTimeString(
                                        'id-ID', {
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        });
                            } catch (e) {
                                timeFormatted = schedule.waktu_mulai;
                            }
                        }

                        // Build option text with correct property names
                        // Handle both possible property name formats from stored procedures
                        const judul = schedule.judul || schedule['Judul Film'] || 'Film';
                        const studio = schedule.nama_studio || schedule['Studio'] || 'Studio';
                        const harga = schedule.harga_film || schedule['Harga Tiket'] || schedule.harga || 0;

                        option.textContent =
                            `${judul} - ${studio} (${timeFormatted}) - Rp ${parseInt(harga).toLocaleString('id-ID')}`;

                        scheduleSelect.appendChild(option);
                    });

                    scheduleSelect.disabled = false;
                } else {
                    scheduleSelect.innerHTML = '<option value="">Tidak ada jadwal tersedia untuk cabang ini</option>';
                    showAlert('Tidak ada jadwal yang tersedia untuk cabang ini', 'info');
                }
            } catch (error) {
                console.error('Error loading schedules:', error);
                scheduleSelect.innerHTML = '<option value="">Error loading schedules</option>';
                showAlert('Gagal memuat jadwal. Silakan coba lagi.', 'error');
            }
        }

        function updateScheduleInfo() {
            const select = document.getElementById('movie_schedule');
            const scheduleInfo = document.getElementById('schedule_info');

            if (select.value) {
                try {
                    selectedSchedule = JSON.parse(select.value);

                    // Update info display using correct property names
                    // Handle both possible property name formats from stored procedures
                    const studio = selectedSchedule.nama_studio || selectedSchedule['Studio'] || 'Studio';
                    const durasi = selectedSchedule.durasi_menit || selectedSchedule['Durasi (Menit)'] || selectedSchedule
                        .durasi || 0;
                    const rating = selectedSchedule.rating_usia || selectedSchedule['Rating'] || 'G';
                    const harga = selectedSchedule.harga_film || selectedSchedule['Harga Tiket'] || selectedSchedule
                        .harga || 0;

                    document.getElementById('selected_studio').textContent = studio;
                    document.getElementById('selected_duration').textContent = durasi + ' menit';

                    // Safe time formatting
                    let timeFormatted = 'N/A';
                    if (selectedSchedule.waktu_mulai) {
                        try {
                            timeFormatted = new Date('2000-01-01T' + selectedSchedule.waktu_mulai).toLocaleTimeString(
                                'id-ID', {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });
                        } catch (e) {
                            timeFormatted = selectedSchedule.waktu_mulai;
                        }
                    }
                    document.getElementById('selected_time').textContent = timeFormatted;

                    document.getElementById('selected_rating').textContent = rating;
                    document.getElementById('selected_price').textContent = 'Rp ' + parseInt(harga).toLocaleString('id-ID');

                    scheduleInfo.classList.remove('hidden');

                    // Reset booked seats array and selected seats for new schedule
                    bookedSeats = [];
                    selectedSeats = [];
                    updateSelectedSeatsDisplay();

                    // Load seat map for selected schedule using id_jadwal
                    // The stored procedure will handle both seat layout and booking status
                    const jadwalId = selectedSchedule.id_jadwal || selectedSchedule.ID || selectedSchedule.id;
                    if (jadwalId) {
                        loadSeatMap(jadwalId);
                    } else {
                        console.error('No valid jadwal ID found in selected schedule');
                        generateDefaultSeatMap();
                    }

                    calculateTotal();
                } catch (error) {
                    console.error('Error parsing schedule data:', error);
                    showAlert('Data jadwal tidak valid', 'error');
                    scheduleInfo.classList.add('hidden');
                    selectedSchedule = null;
                    clearSeatMap();
                }
            } else {
                scheduleInfo.classList.add('hidden');
                selectedSchedule = null;
                clearSeatMap();
            }
        }

        async function loadSeatMap(jadwalId) {
            const seatMapContainer = document.getElementById('seat_map');
            seatMapContainer.innerHTML = '<div class="text-center text-gray-500 py-4">Loading seat map...</div>';

            try {
                // Reset booked seats array for new schedule
                bookedSeats = [];

                // Get seat map from stored procedure
                const response = await fetch(`/kasir/seatmap/${jadwalId}`);

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();

                if (data.success && data.seat_map && data.seat_map.length > 0) {
                    generateSeatMapFromData(data.seat_map);
                } else {
                    console.warn('No seat map data from API, using fallback');
                    // Fallback to default seat map
                    generateDefaultSeatMap();
                    showAlert('Menggunakan layout kursi default. Data dari sistem tidak tersedia.', 'info');
                }
            } catch (error) {
                console.error('Error loading seat map:', error);
                // Fallback to default seat map
                generateDefaultSeatMap();
                showAlert('Gagal memuat peta kursi. Menggunakan layout default.', 'error');
            }
        }

        function generateSeatMapFromData(seatMapData) {
            const seatMapContainer = document.getElementById('seat_map');
            seatMapContainer.innerHTML = '';

            // Group seats by row
            const seatsByRow = {};
            seatMapData.forEach(seat => {
                // Handle both property formats from stored procedures
                const row = seat.row || seat.no_baris;
                if (!seatsByRow[row]) {
                    seatsByRow[row] = [];
                }
                seatsByRow[row].push(seat);
            });

            // Get row labels (sort numerically)
            const rowLabels = Object.keys(seatsByRow).sort((a, b) => parseInt(a) - parseInt(b));
            const maxSeatsPerRow = Math.max(...Object.values(seatsByRow).map(row => row.length));

            // Set grid layout
            seatMapContainer.className = 'grid gap-1 justify-center';
            seatMapContainer.style.gridTemplateColumns = `repeat(${maxSeatsPerRow + 2}, 1fr)`;

            rowLabels.forEach(rowNum => {
                const seats = seatsByRow[rowNum].sort((a, b) => {
                    const colA = a.col || a.no_kolom;
                    const colB = b.col || b.no_kolom;
                    return parseInt(colA) - parseInt(colB);
                });

                // Row label (left)
                const rowLabelLeft = document.createElement('div');
                rowLabelLeft.className = 'text-center font-medium text-gray-600 flex items-center justify-center';
                rowLabelLeft.textContent = String.fromCharCode(64 + parseInt(
                    rowNum)); // Convert 1,2,3... to A,B,C...
                seatMapContainer.appendChild(rowLabelLeft);

                // Add seats for this row
                seats.forEach(seat => {
                    const seatElement = document.createElement('div');
                    seatElement.className =
                        'w-8 h-8 border rounded text-xs flex items-center justify-center font-medium transition-all duration-200 cursor-pointer';

                    // Handle both property formats from stored procedures
                    const seatCode = seat.seat_id || seat.seat_code;
                    const column = seat.col || seat.no_kolom;
                    const status = seat.status || seat.status_kursi;

                    seatElement.textContent = column;
                    seatElement.setAttribute('data-seat', seatCode);

                    // Set initial status based on data from stored procedure
                    if (status === 'booked' || status === 'Booked') {
                        seatElement.className +=
                            ' bg-red-200 border-red-400 text-red-800 cursor-not-allowed';
                        seatElement.onclick = null;
                        bookedSeats.push(seatCode);
                    } else {
                        seatElement.className +=
                            ' bg-green-200 border-green-400 text-green-800 hover:bg-green-300 hover:scale-110';
                        seatElement.onclick = () => toggleSeat(seatCode);
                    }

                    seatMapContainer.appendChild(seatElement);
                });

                // Row label (right)
                const rowLabelRight = document.createElement('div');
                rowLabelRight.className = 'text-center font-medium text-gray-600 flex items-center justify-center';
                rowLabelRight.textContent = String.fromCharCode(64 + parseInt(rowNum));
                seatMapContainer.appendChild(rowLabelRight);
            });
        }

        function generateDefaultSeatMap() {
            const seatMapContainer = document.getElementById('seat_map');
            seatMapContainer.innerHTML = '';
            seatMapContainer.className = 'grid gap-1 justify-center';
            seatMapContainer.style.gridTemplateColumns = 'repeat(14, 1fr)';

            const rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
            const seatsPerRow = 12;

            rows.forEach(row => {
                // Row label (left)
                const rowLabelLeft = document.createElement('div');
                rowLabelLeft.className = 'text-center font-medium text-gray-600 flex items-center justify-center';
                rowLabelLeft.textContent = row;
                seatMapContainer.appendChild(rowLabelLeft);

                // Seats in the row
                for (let seatNum = 1; seatNum <= seatsPerRow; seatNum++) {
                    const seatId = row + seatNum.toString().padStart(2, '0');
                    const seatElement = document.createElement('div');
                    seatElement.className =
                        'w-8 h-8 border rounded cursor-pointer text-xs flex items-center justify-center font-medium transition-all duration-200 hover:scale-110 bg-green-200 border-green-400 text-green-800 hover:bg-green-300';
                    seatElement.textContent = seatNum;
                    seatElement.setAttribute('data-seat', seatId);
                    seatElement.onclick = () => toggleSeat(seatId);
                    seatMapContainer.appendChild(seatElement);
                }

                // Row label (right)
                const rowLabelRight = document.createElement('div');
                rowLabelRight.className = 'text-center font-medium text-gray-600 flex items-center justify-center';
                rowLabelRight.textContent = row;
                seatMapContainer.appendChild(rowLabelRight);
            });
        }



        async function loadBookedSeats(jadwalId) {
            if (!jadwalId) {
                return; // No need for demo data since seat map already has status
            }

            try {
                const response = await fetch(`/kasir/seats/booked/${jadwalId}`);
                const data = await response.json();

                if (data.success) {
                    // The seat map already includes booking status from stored procedure
                    // This function is mainly for fallback or additional processing
                    console.log('Booked seats loaded:', data.booked_seats);
                } else {
                    console.error('Failed to load booked seats:', data.message);
                }
            } catch (error) {
                console.error('Error loading booked seats:', error);
            }
        }

        function updateSeatMapWithBookedSeats() {
            // Update visual indicators for booked seats (fallback function)
            bookedSeats.forEach(seatId => {
                const seatElement = document.querySelector(`[data-seat="${seatId}"]`);
                if (seatElement) {
                    seatElement.className =
                        'w-8 h-8 border rounded text-xs flex items-center justify-center font-medium transition-all duration-200 bg-red-200 border-red-400 text-red-800 cursor-not-allowed';
                    seatElement.onclick = null; // Disable click for booked seats
                }
            });
        }

        function toggleSeat(seatId) {
            if (bookedSeats.includes(seatId)) {
                return;
            }

            const seatElement = document.querySelector(`[data-seat="${seatId}"]`);
            const seatIndex = selectedSeats.indexOf(seatId);

            if (seatIndex > -1) {
                // Deselect seat
                selectedSeats.splice(seatIndex, 1);
                seatElement.className =
                    'w-8 h-8 border rounded cursor-pointer text-xs flex items-center justify-center font-medium transition-all duration-200 hover:scale-110 bg-green-200 border-green-400 text-green-800 hover:bg-green-300';
            } else {
                // Select seat
                if (selectedSeats.length >= maxTickets) {
                    alert(`Anda hanya dapat memilih maksimal ${maxTickets} kursi`);
                    return;
                }
                selectedSeats.push(seatId);
                seatElement.className =
                    'w-8 h-8 border rounded cursor-pointer text-xs flex items-center justify-center font-medium transition-all duration-200 hover:scale-110 bg-blue-200 border-blue-400 text-blue-800';
            }

            updateSelectedSeatsDisplay();
            calculateTotal();
        }

        function updateTicketCount() {
            maxTickets = parseInt(document.getElementById('ticket_count').value);

            while (selectedSeats.length > maxTickets) {
                const removedSeat = selectedSeats.pop();
                const seatElement = document.querySelector(`[data-seat="${removedSeat}"]`);
                if (seatElement) {
                    seatElement.className =
                        'w-8 h-8 border rounded cursor-pointer text-xs flex items-center justify-center font-medium transition-all duration-200 hover:scale-110 bg-green-200 border-green-400 text-green-800 hover:bg-green-300';
                }
            }

            updateSelectedSeatsDisplay();
            calculateTotal();
        }

        function updateSelectedSeatsDisplay() {
            const display = document.getElementById('selected_seats_display');
            const hiddenInput = document.getElementById('selected_seats_input');

            if (selectedSeats.length > 0) {
                display.textContent = selectedSeats.sort().join(', ');
                hiddenInput.value = JSON.stringify(selectedSeats);
            } else {
                display.textContent = '-';
                hiddenInput.value = '';
            }
        }

        function clearSelectedSeats() {
            selectedSeats.forEach(seatId => {
                const seatElement = document.querySelector(`[data-seat="${seatId}"]`);
                if (seatElement && !bookedSeats.includes(seatId)) {
                    seatElement.className =
                        'w-8 h-8 border rounded cursor-pointer text-xs flex items-center justify-center font-medium transition-all duration-200 hover:scale-110 bg-green-200 border-green-400 text-green-800 hover:bg-green-300';
                }
            });

            selectedSeats = [];
            updateSelectedSeatsDisplay();
            calculateTotal();
        }

        function clearSeatMap() {
            const seatMapContainer = document.getElementById('seat_map');
            seatMapContainer.innerHTML =
                '<div class="text-center text-gray-500 py-8">Pilih jadwal untuk melihat peta kursi</div>';
            selectedSeats = [];
            updateSelectedSeatsDisplay();
        }

        function calculateTotal() {
            const ticketCount = selectedSeats.length;
            document.getElementById('total_tickets').textContent = ticketCount;

            if (selectedSchedule && ticketCount > 0) {
                // Handle different property formats from stored procedures
                const ticketPrice = parseInt(selectedSchedule.harga_film || selectedSchedule['Harga Tiket'] ||
                    selectedSchedule.harga || 0);
                const totalAmount = ticketCount * ticketPrice;
                document.getElementById('total_amount').textContent = 'Rp ' + totalAmount.toLocaleString('id-ID');
            } else {
                document.getElementById('total_amount').textContent = 'Rp 0';
            }
        }

        function processNewTicket() {
            const form = document.getElementById('newTicketForm');
            const formData = new FormData(form);
            const submitBtn = document.querySelector('[onclick="processNewTicket()"]');

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            if (!selectedSchedule) {
                showAlert('Silakan pilih film dan jadwal terlebih dahulu', 'error');
                return;
            }

            if (selectedSeats.length === 0) {
                showAlert('Silakan pilih kursi terlebih dahulu', 'error');
                return;
            }

            const maxTickets = parseInt(document.getElementById('ticket_count').value);
            if (selectedSeats.length !== maxTickets) {
                showAlert(`Silakan pilih ${maxTickets} kursi sesuai jumlah tiket`, 'error');
                return;
            }

            // Validate customer phone
            const customerPhone = formData.get('customer_phone');
            if (!customerPhone || customerPhone.length < 8) {
                showAlert('Nomor HP harus minimal 8 karakter', 'error');
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML =
                '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...';

            // Handle different property formats for price calculation
            const ticketPrice = parseInt(selectedSchedule.harga_film || selectedSchedule['Harga Tiket'] || selectedSchedule
                .harga || 0);

            const transactionData = {
                customer_name: formData.get('customer_name'),
                customer_email: formData.get('customer_email') || '',
                customer_phone: formData.get('customer_phone'),
                schedule: selectedSchedule,
                seats: selectedSeats,
                payment_method: formData.get('payment_method'),
                ticket_count: selectedSeats.length,
                total_amount: selectedSeats.length * ticketPrice
            };

            fetch('/kasir/tickets/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(transactionData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessModal(data);
                    } else {
                        showAlert('Gagal memproses transaksi: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Terjadi kesalahan saat memproses transaksi', 'error');
                })
                .finally(() => {
                    // Reset loading state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Proses Transaksi';
                });
        }

        // Utility functions for UI feedback
        function showAlert(message, type = 'info') {
            const alertContainer = document.createElement('div');
            alertContainer.className =
                `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

            if (type === 'error') {
                alertContainer.className += ' bg-red-500 text-white';
            } else if (type === 'success') {
                alertContainer.className += ' bg-green-500 text-white';
            } else {
                alertContainer.className += ' bg-blue-500 text-white';
            }

            alertContainer.innerHTML = `
                <div class="flex items-center">
                    <span class="mr-3">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            `;

            document.body.appendChild(alertContainer);

            // Animate in
            setTimeout(() => {
                alertContainer.classList.remove('translate-x-full');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertContainer.parentElement) {
                    alertContainer.classList.add('translate-x-full');
                    setTimeout(() => alertContainer.remove(), 300);
                }
            }, 5000);
        }

        function showSuccessModal(data) {
            closeNewTicketModal();

            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
            modal.innerHTML = `
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2">Transaksi Berhasil!</h3>
                        <div class="mt-2 px-7 py-3">
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <div class="text-sm text-gray-600 mb-2">
                                    <span class="font-medium">ID Transaksi:</span>
                                    <span class="font-mono text-blue-600">#${data.transaction_id}</span>
                                </div>
                                <div class="text-sm text-gray-600 mb-2">
                                    <span class="font-medium">Total Tiket:</span>
                                    <span class="text-green-600">${data.ticket_count} tiket</span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <span class="font-medium">Total Bayar:</span>
                                    <span class="text-green-600 font-bold">Rp ${parseInt(data.total_amount).toLocaleString('id-ID')}</span>
                                </div>
                            </div>
                        </div>
                        <div class="items-center px-4 py-3">
                            <div class="flex space-x-3">
                                <button onclick="printTicket(${data.transaction_id})" class="flex-1 px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    📄 Cetak Tiket
                                </button>
                                <button onclick="closeSuccessModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);
            window.successModal = modal;
        }

        function closeSuccessModal() {
            if (window.successModal) {
                window.successModal.remove();
                window.successModal = null;
            }
            location.reload();
        }

        function printTicket(transactionId) {
            window.open('/kasir/transactions/' + transactionId + '/print', '_blank');
            closeSuccessModal();
        }

        // Close modal when clicking outside
        document.getElementById('newTicketModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeNewTicketModal();
            }
        });
    </script>
</x-app-layout>
