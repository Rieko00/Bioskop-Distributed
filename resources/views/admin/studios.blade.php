<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Studio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header with Add Button -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Daftar Studio</h3>
                        <button id="addStudioBtn"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Tambah Studio
                        </button>
                    </div>

                    <!-- Filter -->
                    <div class="mb-4">
                        <select id="cabangFilter" class="border border-gray-300 rounded px-3 py-2">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabangs as $cabang)
                                <option value="{{ $cabang->id_cabang }}">{{ $cabang->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Studios Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200" id="studiosTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Studio</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cabang</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kapasitas</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dibuat</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($studios as $studio)
                                    <tr data-cabang="{{ $studio->id_cabang }}">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $studio->id_studio }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $studio->nama_studio }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $studio->nama_cabang }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="font-semibold">{{ $studio->kapasitas }}</span> kursi
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($studio->created_at)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="viewSeatMap({{ $studio->id_studio }})"
                                                class="text-green-600 hover:text-green-900 mr-3">View Seat Map</button>
                                            <button onclick="editStudio({{ $studio->id_studio }})"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada data
                                            studio</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Studio Modal -->
    <div id="studioModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-[500px] shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 mb-4" id="modalTitle">Tambah Studio</h3>
                <form id="studioForm">
                    <input type="hidden" id="studioId" name="studioId">

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Cabang</label>
                        <select id="idCabang" name="id_cabang"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                            required>
                            <option value="">Pilih Cabang</option>
                            @foreach ($cabangs as $cabang)
                                <option value="{{ $cabang->id_cabang }}">{{ $cabang->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Studio</label>
                        <input type="text" id="namaStudio" name="nama_studio"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                            required>
                    </div>

                    <div class="mb-4" id="jumlahBarisField">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Baris (A-Z, max 26)</label>
                        <input type="number" id="jumlahBaris" name="jumlah_baris" min="1" max="26"
                            value="10"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                            required onchange="calculateCapacity()">
                        <small class="text-gray-500">Contoh: 10 baris = A sampai J</small>
                    </div>

                    <div class="mb-4" id="jumlahKolomField">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Kolom per Baris (max
                            50)</label>
                        <input type="number" id="jumlahKolom" name="jumlah_kolom_per_baris" min="1"
                            max="50" value="12"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                            required onchange="calculateCapacity()">
                        <small class="text-gray-500">Contoh: 12 kolom = 1 sampai 12</small>
                    </div>

                    <div class="mb-4" id="totalKapasitasField">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Total Kapasitas (otomatis)</label>
                        <div class="flex items-center">
                            <input type="number" id="totalKapasitas" name="total_kapasitas"
                                class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-100 cursor-not-allowed"
                                readonly>
                            <span class="ml-2 text-sm text-gray-600">kursi</span>
                        </div>
                        <small class="text-gray-500">Dihitung otomatis: Baris × Kolom</small>
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

    <!-- Seat Map Modal -->
    <div id="seatMapModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-10 mx-auto p-5 border max-w-4xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900" id="seatMapTitle">Peta Kursi Studio</h3>
                    <button id="closeSeatMapModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div id="seatMapContent" class="text-center">
                    <div id="seatMapLoading" class="py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
                        <p class="mt-2 text-gray-500">Memuat peta kursi...</p>
                    </div>

                    <div id="seatMapDisplay" class="hidden">
                        <div class="mb-4 p-3 bg-gray-100 rounded">
                            <h4 class="font-semibold text-gray-800 mb-1" id="studioInfo"></h4>
                            <p class="text-sm text-gray-600" id="capacityInfo"></p>
                        </div>

                        <!-- Screen indicator -->
                        <div class="mb-6">
                            <div class="bg-gray-800 text-white text-center py-2 px-4 rounded mx-auto w-64">
                                LAYAR
                            </div>
                        </div>

                        <!-- Seat map grid -->
                        <div id="seatGrid" class="inline-block border border-gray-300 p-4 bg-gray-50 rounded">
                            <!-- Seats will be dynamically loaded here -->
                        </div>

                        <div class="mt-4 text-sm text-gray-600">
                            <div class="flex justify-center items-center gap-4">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 bg-green-200 border border-green-400 rounded mr-2"></div>
                                    <span>Tersedia</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let isEdit = false;

        // Modal Controls
        document.getElementById('addStudioBtn').addEventListener('click', openAddModal);
        document.getElementById('closeModal').addEventListener('click', closeModal);
        document.getElementById('closeSeatMapModal').addEventListener('click', closeSeatMapModal);
        document.getElementById('studioForm').addEventListener('submit', handleSubmit);

        // Add event listeners for capacity calculation
        document.addEventListener('DOMContentLoaded', function() {
            const barisInput = document.getElementById('jumlahBaris');
            const kolomInput = document.getElementById('jumlahKolom');

            if (barisInput && kolomInput) {
                barisInput.addEventListener('input', calculateCapacity);
                kolomInput.addEventListener('input', calculateCapacity);
                barisInput.addEventListener('change', calculateCapacity);
                kolomInput.addEventListener('change', calculateCapacity);
            }
        });

        // Calculate capacity function
        function calculateCapacity() {
            const baris = parseInt(document.getElementById('jumlahBaris').value) || 0;
            const kolom = parseInt(document.getElementById('jumlahKolom').value) || 0;
            const total = baris * kolom;
            document.getElementById('totalKapasitas').value = total;
        }

        function openAddModal() {
            isEdit = false;
            document.getElementById('modalTitle').textContent = 'Tambah Studio';
            document.getElementById('studioForm').reset();
            document.getElementById('studioId').value = '';

            // Show row/column fields for adding new studio
            document.getElementById('jumlahBarisField').style.display = 'block';
            document.getElementById('jumlahKolomField').style.display = 'block';
            document.getElementById('totalKapasitasField').style.display = 'block';

            // Set required attributes
            document.getElementById('jumlahBaris').required = true;
            document.getElementById('jumlahKolom').required = true;

            // Set default values and calculate initial capacity
            document.getElementById('jumlahBaris').value = 10;
            document.getElementById('jumlahKolom').value = 12;
            calculateCapacity();

            document.getElementById('studioModal').classList.remove('hidden');
        }

        function editStudio(id) {
            isEdit = true;
            document.getElementById('modalTitle').textContent = 'Edit Studio';

            // Hide row/column fields for editing existing studio
            document.getElementById('jumlahBarisField').style.display = 'none';
            document.getElementById('jumlahKolomField').style.display = 'none';
            document.getElementById('totalKapasitasField').style.display = 'none';

            // Remove required attributes
            document.getElementById('jumlahBaris').required = false;
            document.getElementById('jumlahKolom').required = false;

            // Find the studio data from the table row
            const row = document.querySelector(`button[onclick="editStudio(${id})"]`).closest('tr');
            const cells = row.cells;

            document.getElementById('studioId').value = id;
            document.getElementById('namaStudio').value = cells[1].textContent.trim();
            document.getElementById('idCabang').value = row.getAttribute('data-cabang');

            document.getElementById('studioModal').classList.remove('hidden');
        }

        function deleteStudio(id) {
            if (confirm(
                    'Apakah Anda yakin ingin menghapus studio ini? Semua jadwal tayang yang terkait juga akan terpengaruh.'
                )) {
                fetch(`/admin/studios/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Studio berhasil dihapus');
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

            if (isEdit) {
                // For edit, use the original endpoint (may need different SP for update)
                const url = `/admin/studios/${data.studioId}`;
                const editData = {
                    id_cabang: data.id_cabang,
                    nama_studio: data.nama_studio
                };

                fetch(url, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(editData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Studio berhasil diupdate');
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
            } else {
                // For add, use new endpoint with seat generation
                const url = '/admin/studios';
                const addData = {
                    id_cabang: data.id_cabang,
                    nama_studio: data.nama_studio,
                    jumlah_baris: data.jumlah_baris,
                    jumlah_kolom_per_baris: data.jumlah_kolom_per_baris
                };

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(addData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
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
        }

        function closeModal() {
            document.getElementById('studioModal').classList.add('hidden');
        }

        // Cabang Filter
        document.getElementById('cabangFilter').addEventListener('change', function() {
            const cabangId = this.value;
            const rows = document.querySelectorAll('#studiosTable tbody tr');

            rows.forEach(row => {
                if (cabangId === '') {
                    row.style.display = '';
                } else {
                    const rowCabangId = row.getAttribute('data-cabang');
                    if (rowCabangId && rowCabangId === cabangId) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });

        // Seat Map Functions
        function viewSeatMap(studioId) {
            document.getElementById('seatMapModal').classList.remove('hidden');
            document.getElementById('seatMapLoading').classList.remove('hidden');
            document.getElementById('seatMapDisplay').classList.add('hidden');

            fetch(`/admin/studios/${studioId}/seatmap`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('seatMapLoading').classList.add('hidden');

                    if (data.success) {
                        displaySeatMap(data.studio, data.seats);
                    } else {
                        alert('Error: ' + data.message);
                        closeSeatMapModal();
                    }
                })
                .catch(error => {
                    document.getElementById('seatMapLoading').classList.add('hidden');
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat peta kursi');
                    closeSeatMapModal();
                });
        }

        function displaySeatMap(studio, seats) {
            // Update modal title and info
            document.getElementById('seatMapTitle').textContent = `Peta Kursi - ${studio.nama_studio}`;
            document.getElementById('studioInfo').textContent = `${studio.nama_studio}`;
            document.getElementById('capacityInfo').textContent = `Total Kapasitas: ${studio.kapasitas} kursi`;

            // Group seats by row
            const seatsByRow = {};
            seats.forEach(seat => {
                if (!seatsByRow[seat.no_baris]) {
                    seatsByRow[seat.no_baris] = [];
                }
                seatsByRow[seat.no_baris].push(seat);
            });

            // Generate seat map HTML
            const seatGrid = document.getElementById('seatGrid');
            let html = '';

            // Get all unique rows and sort them alphabetically
            const rows = Object.keys(seatsByRow).sort();

            rows.forEach(row => {
                html += `<div class="flex items-center mb-2">`;
                html += `<div class="w-8 text-sm font-bold text-gray-700 text-center mr-2">${row}</div>`;

                const rowSeats = seatsByRow[row].sort((a, b) => parseInt(a.no_kolom) - parseInt(b.no_kolom));

                rowSeats.forEach(seat => {
                    html += `<div class="w-8 h-8 m-1 bg-green-200 border border-green-400 rounded flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-green-300" 
                             title="Kursi ${seat.seat_code}">
                             ${seat.no_kolom}
                           </div>`;
                });

                html += `</div>`;
            });

            seatGrid.innerHTML = html;
            document.getElementById('seatMapDisplay').classList.remove('hidden');
        }

        function closeSeatMapModal() {
            document.getElementById('seatMapModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
