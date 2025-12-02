<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Film') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header with Add Button -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Daftar Film</h3>
                        <button id="addFilmBtn"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Tambah Film
                        </button>
                    </div>

                    <!-- Films Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Judul</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rating Usia</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Durasi</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Harga</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dibuat</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($films as $film)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $film->id_film }}</td>
                                        <td class="px-6 py-4 font-medium">
                                            <div class="text-sm font-semibold text-gray-900">{{ $film->judul }}</div>
                                            <div class="text-sm text-gray-500 truncate max-w-xs"
                                                title="{{ $film->sinopsis }}">
                                                {{ Str::limit($film->sinopsis, 50) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $film->rating_usia === 'G'
                                                ? 'bg-green-100 text-green-800'
                                                : ($film->rating_usia === 'PG'
                                                    ? 'bg-yellow-100 text-yellow-800'
                                                    : ($film->rating_usia === 'PG-13'
                                                        ? 'bg-orange-100 text-orange-800'
                                                        : 'bg-red-100 text-red-800')) }}">
                                                {{ $film->rating_usia }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            {{ $film->durasi_menit }} menit
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-semibold">
                                            Rp {{ number_format($film->harga_film ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($film->created_at)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="viewFilm({{ $film->id_film }})"
                                                class="text-blue-600 hover:text-blue-900 mr-3">Detail</button>
                                            <button onclick="editFilm({{ $film->id_film }})"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                            <button onclick="deleteFilm({{ $film->id_film }})"
                                                class="text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data
                                            film</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Film Modal -->
    <div id="filmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 mb-4" id="modalTitle">Tambah Film</h3>
                <form id="filmForm">
                    <input type="hidden" id="filmId" name="filmId">

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Judul Film</label>
                        <input type="text" id="judul" name="judul"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Sinopsis</label>
                        <textarea id="sinopsis" name="sinopsis" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500" required></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Durasi (menit)</label>
                            <input type="number" id="durasiMenit" name="durasi_menit" min="1" max="999"
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                                required>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Rating Usia</label>
                            <select id="ratingUsia" name="rating_usia"
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                                required>
                                <option value="">Pilih Rating</option>
                                <option value="G">G (General)</option>
                                <option value="PG">PG (Parental Guidance)</option>
                                <option value="PG-13">PG-13 (13+)</option>
                                <option value="R">R (Restricted)</option>
                                <option value="17+">17+ (Dewasa)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Harga Film (Rp)</label>
                        <input type="number" id="hargaFilm" name="harga_film" min="0" step="1000"
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

    <!-- Film Detail Modal -->
    <div id="filmDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Detail Film</h3>
                <div id="filmDetails">
                    <!-- Film details will be populated here -->
                </div>
                <div class="flex justify-end mt-4">
                    <button id="closeDetailModal"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let isEdit = false;

        // Modal Controls
        document.getElementById('addFilmBtn').addEventListener('click', openAddModal);
        document.getElementById('closeModal').addEventListener('click', closeModal);
        document.getElementById('closeDetailModal').addEventListener('click', closeDetailModal);
        document.getElementById('filmForm').addEventListener('submit', handleSubmit);

        function openAddModal() {
            isEdit = false;
            document.getElementById('modalTitle').textContent = 'Tambah Film';
            document.getElementById('filmForm').reset();
            document.getElementById('filmId').value = '';
            document.getElementById('filmModal').classList.remove('hidden');
        }

        function editFilm(id) {
            isEdit = true;
            document.getElementById('modalTitle').textContent = 'Edit Film';

            fetch(`/admin/films/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const film = data.data;
                        document.getElementById('filmId').value = film.id_film;
                        document.getElementById('judul').value = film.judul;
                        document.getElementById('sinopsis').value = film.sinopsis;
                        document.getElementById('durasiMenit').value = film.durasi_menit;
                        document.getElementById('ratingUsia').value = film.rating_usia;
                        document.getElementById('hargaFilm').value = film.harga_film;
                        document.getElementById('filmModal').classList.remove('hidden');
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
        }

        function viewFilm(id) {
            fetch(`/admin/films/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const film = data.data;
                        const createdAt = new Date(film.created_at).toLocaleDateString('id-ID', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });

                        document.getElementById('filmDetails').innerHTML = `
                            <div class="space-y-3">
                                <div>
                                    <label class="font-semibold text-gray-700">Judul:</label>
                                    <p class="text-gray-900">${film.judul}</p>
                                </div>
                                <div>
                                    <label class="font-semibold text-gray-700">Sinopsis:</label>
                                    <p class="text-gray-900 text-sm">${film.sinopsis}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="font-semibold text-gray-700">Durasi:</label>
                                        <p class="text-gray-900">${film.durasi_menit} menit</p>
                                    </div>
                                    <div>
                                        <label class="font-semibold text-gray-700">Rating:</label>
                                        <p class="text-gray-900">${film.rating_usia}</p>
                                    </div>
                                </div>
                                <div>
                                    <label class="font-semibold text-gray-700">Harga:</label>
                                    <p class="text-gray-900">Rp ${Number(film.harga_film).toLocaleString('id-ID')}</p>
                                </div>
                                <div>
                                    <label class="font-semibold text-gray-700">Dibuat:</label>
                                    <p class="text-gray-900 text-sm">${createdAt}</p>
                                </div>
                            </div>
                        `;
                        document.getElementById('filmDetailModal').classList.remove('hidden');
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
        }

        function deleteFilm(id) {
            if (confirm(
                    'Apakah Anda yakin ingin menghapus film ini? Semua jadwal tayang yang terkait juga akan terpengaruh.'
                    )) {
                fetch(`/admin/films/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Film berhasil dihapus');
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

            const url = isEdit ? `/admin/films/${data.filmId}` : '/admin/films';
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
                        alert(isEdit ? 'Film berhasil diupdate' : 'Film berhasil ditambahkan');
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
            document.getElementById('filmModal').classList.add('hidden');
        }

        function closeDetailModal() {
            document.getElementById('filmDetailModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
