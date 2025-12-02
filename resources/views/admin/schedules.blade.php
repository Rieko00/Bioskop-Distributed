<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Jadwal Tayang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header with Add Button -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Daftar Jadwal Tayang</h3>
                        <button id="addScheduleBtn"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Tambah Jadwal
                        </button>
                    </div>

                    <!-- Filter -->
                    <div class="mb-4 flex gap-4">
                        <select id="cabangFilter" class="border border-gray-300 rounded px-3 py-2">
                            <option value="">Semua Cabang</option>
                            @foreach ($studios as $studio)
                                @if (!isset($uniqueCabangs[$studio->nama_cabang]))
                                    @php $uniqueCabangs[$studio->nama_cabang] = true @endphp
                                    <option value="{{ $studio->nama_cabang }}">{{ $studio->nama_cabang }}</option>
                                @endif
                            @endforeach
                        </select>

                        <input type="date" id="dateFilter" class="border border-gray-300 rounded px-3 py-2"
                            value="{{ date('Y-m-d') }}">
                    </div>

                    <!-- Schedules Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200" id="schedulesTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Film</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cabang</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Studio</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Waktu</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php $uniqueCabangs = []; @endphp
                                @forelse($jadwals as $jadwal)
                                    <tr data-cabang="{{ $jadwal->nama_cabang }}"
                                        data-date="{{ $jadwal->tanggal_tayang }}">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $jadwal->id_jadwal }}</td>
                                        <td class="px-6 py-4 font-medium">
                                            <div class="text-sm font-semibold text-gray-900">{{ $jadwal->nama_film }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $jadwal->nama_cabang }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $jadwal->nama_studio }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($jadwal->tanggal_tayang)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm">
                                                <span
                                                    class="font-semibold">{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="editSchedule({{ $jadwal->id_jadwal }})"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data
                                            jadwal tayang</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Schedule Modal -->
    <div id="scheduleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 mb-4" id="modalTitle">Tambah Jadwal Tayang</h3>
                <form id="scheduleForm">
                    <input type="hidden" id="scheduleId" name="scheduleId">

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Film</label>
                        <select id="idFilm" name="id_film"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                            required>
                            <option value="">Pilih Film</option>
                            @foreach ($films as $film)
                                <option value="{{ $film->id_film }}" data-duration="{{ $film->durasi_menit }}">
                                    {{ $film->judul }} ({{ $film->durasi_menit }} menit)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Studio</label>
                        <select id="idStudio" name="id_studio"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                            required>
                            <option value="">Pilih Studio</option>
                            @foreach ($studios as $studio)
                                <option value="{{ $studio->id_studio }}">
                                    {{ $studio->nama_cabang }} - {{ $studio->nama_studio }}
                                    ({{ $studio->tipe_studio }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Tayang</label>
                        <input type="date" id="tanggalTayang" name="tanggal_tayang"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                            required min="{{ date('Y-m-d') }}">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Waktu Mulai</label>
                            <input type="time" id="waktuMulai" name="waktu_mulai"
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                                required>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4">
                        <button type="button" id="closeModal"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let isEdit = false;

        // Modal Controls
        document.getElementById('addScheduleBtn').addEventListener('click', openAddModal);
        document.getElementById('closeModal').addEventListener('click', closeModal);
        document.getElementById('scheduleForm').addEventListener('submit', handleSubmit);

        // Auto calculate end time when start time or film changes

        function openAddModal() {
            isEdit = false;
            document.getElementById('modalTitle').textContent = 'Tambah Jadwal Tayang';
            document.getElementById('scheduleForm').reset();
            document.getElementById('scheduleId').value = '';
            document.getElementById('tanggalTayang').value = new Date().toISOString().split('T')[0];
            document.getElementById('scheduleModal').classList.remove('hidden');
        }

        function editSchedule(id) {
            isEdit = true;
            document.getElementById('modalTitle').textContent = 'Edit Jadwal Tayang';

            // Find the schedule data from the table row
            const row = document.querySelector(`button[onclick="editSchedule(${id})"]`).closest('tr');
            const cells = row.cells;

            // Get film and studio IDs from the visible data (this is a simplified approach)
            // In a real application, you might want to make an API call to get full details
            const filmText = cells[1].textContent.trim();
            const studioText = `${cells[2].querySelector('span').textContent} - ${cells[3].textContent}`;
            const dateText = cells[4].textContent.trim();
            const timeText = cells[5].textContent.trim();

            // Parse date
            const dateParts = dateText.split('/');
            const formattedDate = `${dateParts[2]}-${dateParts[1].padStart(2, '0')}-${dateParts[0].padStart(2, '0')}`;

            // Parse time
            const timeParts = timeText.split(' - ');
            const startTime = timeParts[0];

            document.getElementById('scheduleId').value = id;
            document.getElementById('tanggalTayang').value = formattedDate;
            document.getElementById('waktuMulai').value = startTime;

            // Select appropriate film and studio (simplified - may need adjustment)
            const filmOptions = document.getElementById('idFilm').options;
            for (let i = 0; i < filmOptions.length; i++) {
                if (filmOptions[i].textContent.includes(filmText.split('\n')[0])) {
                    document.getElementById('idFilm').value = filmOptions[i].value;
                    break;
                }
            }

            const studioOptions = document.getElementById('idStudio').options;
            for (let i = 0; i < studioOptions.length; i++) {
                if (studioOptions[i].textContent.includes(studioText)) {
                    document.getElementById('idStudio').value = studioOptions[i].value;
                    break;
                }
            }

            document.getElementById('scheduleModal').classList.remove('hidden');
        }

        function calculateEndTime() {
            const startTime = document.getElementById('waktuMulai').value;
            const filmSelect = document.getElementById('idFilm');
            const selectedOption = filmSelect.options[filmSelect.selectedIndex];

            if (startTime && selectedOption && selectedOption.dataset.duration) {
                const duration = parseInt(selectedOption.dataset.duration);
                const [hours, minutes] = startTime.split(':').map(Number);

                const startDate = new Date();
                startDate.setHours(hours, minutes, 0, 0);

                const endDate = new Date(startDate.getTime() + (duration + 15) * 60000); // Add 15 minutes for cleanup

                const endHours = endDate.getHours().toString().padStart(2, '0');
                const endMinutes = endDate.getMinutes().toString().padStart(2, '0');
            }
        }

        function deleteSchedule(id) {
            if (confirm(
                    'Apakah Anda yakin ingin menghapus jadwal ini? Jika ada tiket yang sudah terjual, jadwal tidak dapat dihapus.'
                )) {
                fetch(`/admin/schedules/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Jadwal berhasil dihapus');
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    });
            }
        }

        function handleSubmit(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);

            const url = isEdit ? `/admin/schedules/${data.scheduleId}` : '/admin/schedules';
            const method = isEdit ? 'PUT' : 'POST';

            fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(isEdit ? 'Jadwal berhasil diupdate' : 'Jadwal berhasil ditambahkan');
                        closeModal();
                        location.reload();
                    } else {
                        if (data.errors) {
                            const errors = Object.values(data.errors).flat();
                            alert('Validation errors:\n' + errors.join('\n'));
                        } else {
                            alert('Error: ' + (data.message || 'Something went wrong'));
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan data');
                });
        }

        function closeModal() {
            document.getElementById('scheduleModal').classList.add('hidden');
        }

        // Filters
        document.getElementById('cabangFilter').addEventListener('change', applyFilters);
        document.getElementById('dateFilter').addEventListener('change', applyFilters);

        function applyFilters() {
            const cabang = document.getElementById('cabangFilter').value;
            const date = document.getElementById('dateFilter').value;
            const rows = document.querySelectorAll('#schedulesTable tbody tr');

            rows.forEach(row => {
                let show = true;

                if (cabang && !row.getAttribute('data-cabang')?.includes(cabang)) {
                    show = false;
                }

                if (date) {
                    const rowDate = row.getAttribute('data-date');
                    if (rowDate) {
                        const formattedRowDate = new Date(rowDate).toISOString().split('T')[0];
                        if (formattedRowDate !== date) {
                            show = false;
                        }
                    }
                }

                row.style.display = show ? '' : 'none';
            });
        }
    </script>
</x-app-layout>
