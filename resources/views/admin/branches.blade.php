<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Cabang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header with Add Button -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Daftar Cabang</h3>
                        <button id="addCabangBtn"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Tambah Cabang
                        </button>
                    </div>

                    <!-- Branches Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Cabang</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Alamat</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kode Kota</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dibuat</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($cabangs as $cabang)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $cabang->id_cabang }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $cabang->nama_cabang }}
                                        </td>
                                        <td class="px-6 py-4">{{ $cabang->alamat }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $cabang->kode_cabang_kota }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($cabang->created_at)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="editCabang({{ $cabang->id_cabang }})"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada data
                                            cabang</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Cabang Modal -->
    <div id="cabangModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 mb-4" id="modalTitle">Tambah Cabang</h3>
                <form id="cabangForm">
                    <input type="hidden" id="cabangId" name="cabangId">

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Cabang</label>
                        <input type="text" id="namaCabang" name="nama_cabang"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kode Cabang Kota</label>
                        <input type="text" id="kodeCabangKota" name="kode_cabang_kota"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                            maxlength="10" required>
                        <small class="text-gray-600">Maksimal 10 karakter</small>
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
        document.getElementById('addCabangBtn').addEventListener('click', openAddModal);
        document.getElementById('closeModal').addEventListener('click', closeModal);
        document.getElementById('cabangForm').addEventListener('submit', handleSubmit);

        function openAddModal() {
            isEdit = false;
            document.getElementById('modalTitle').textContent = 'Tambah Cabang';
            document.getElementById('cabangForm').reset();
            document.getElementById('cabangId').value = '';
            document.getElementById('cabangModal').classList.remove('hidden');
        }

        function editCabang(id) {
            isEdit = true;
            document.getElementById('modalTitle').textContent = 'Edit Cabang';

            fetch(`/admin/branches/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const cabang = data.data;
                        document.getElementById('cabangId').value = cabang.id_cabang;
                        document.getElementById('namaCabang').value = cabang.nama_cabang;
                        document.getElementById('alamat').value = cabang.alamat;
                        document.getElementById('kodeCabangKota').value = cabang.kode_cabang_kota;
                        document.getElementById('cabangModal').classList.remove('hidden');
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
        }

        function deleteCabang(id) {
            if (confirm('Apakah Anda yakin ingin menghapus cabang ini? Semua studio yang terkait juga akan terpengaruh.')) {
                fetch(`/admin/branches/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Cabang berhasil dihapus');
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

            const url = isEdit ? `/admin/branches/${data.cabangId}` : '/admin/branches';
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
                        alert(isEdit ? 'Cabang berhasil diupdate' : 'Cabang berhasil ditambahkan');
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
            document.getElementById('cabangModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
