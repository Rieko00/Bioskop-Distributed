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
                                        Tipe Studio</th>
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $studio->tipe_studio }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="font-semibold">{{ $studio->kapasitas }}</span> kursi
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($studio->created_at)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="editStudio({{ $studio->id_studio }})"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                            <button onclick="deleteStudio({{ $studio->id_studio }})"
                                                class="text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data
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
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
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

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Studio</label>
                        <select id="tipeStudio" name="tipe_studio"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                            required>
                            <option value="">Pilih Tipe Studio</option>
                            <option value="Regular">Regular</option>
                            <option value="Premier">Premier</option>
                            <option value="IMAX">IMAX</option>
                            <option value="4DX">4DX</option>
                            <option value="Dolby Atmos">Dolby Atmos</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kapasitas (kursi)</label>
                        <input type="number" id="kapasitas" name="kapasitas" min="1" max="500"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                            required>
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
        document.getElementById('addStudioBtn').addEventListener('click', openAddModal);
        document.getElementById('closeModal').addEventListener('click', closeModal);
        document.getElementById('studioForm').addEventListener('submit', handleSubmit);

        function openAddModal() {
            isEdit = false;
            document.getElementById('modalTitle').textContent = 'Tambah Studio';
            document.getElementById('studioForm').reset();
            document.getElementById('studioId').value = '';
            document.getElementById('studioModal').classList.remove('hidden');
        }

        function editStudio(id) {
            isEdit = true;
            document.getElementById('modalTitle').textContent = 'Edit Studio';

            // Find the studio data from the table row
            const row = document.querySelector(`button[onclick="editStudio(${id})"]`).closest('tr');
            const cells = row.cells;

            document.getElementById('studioId').value = id;
            document.getElementById('namaStudio').value = cells[1].textContent.trim();
            document.getElementById('idCabang').value = row.getAttribute('data-cabang');
            document.getElementById('tipeStudio').value = cells[3].querySelector('span').textContent.trim();
            document.getElementById('kapasitas').value = cells[4].textContent.replace(/\D/g, '');
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

            const url = isEdit ? `/admin/studios/${data.studioId}` : '/admin/studios';
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
                        alert(isEdit ? 'Studio berhasil diupdate' : 'Studio berhasil ditambahkan');
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
    </script>
</x-app-layout>
