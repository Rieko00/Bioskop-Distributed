<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header with Add Button -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Daftar Pengguna</h3>
                        <button id="addUserBtn"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Tambah Pengguna
                        </button>
                    </div>

                    <!-- Filter -->
                    <div class="mb-4">
                        <select id="roleFilter" class="border border-gray-300 rounded px-3 py-2">
                            <option value="">Semua Role</option>
                            <option value="admin">Admin</option>
                            <option value="kasir">Kasir</option>
                        </select>
                    </div>

                    <!-- Users Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200" id="usersTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dibuat</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">
                                            {{ $user->{'Nama User'} ?? ($user->nama_user ?? $user->name) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->Email ?? $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $user->Role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ ucfirst($user->Role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($user->{'Terdaftar Sejak'})->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="editUser({{ $user->id }})"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                            <button onclick="changePassword({{ $user->id }})"
                                                class="text-yellow-600 hover:text-yellow-900 mr-3">Password</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada data
                                            pengguna</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit User Modal -->
    <div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 mb-4" id="modalTitle">Tambah Pengguna</h3>
                <form id="userForm">
                    <input type="hidden" id="userId" name="userId">

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama</label>
                        <input type="text" id="userName" name="name"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                        <input type="email" id="userEmail" name="email"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                            required>
                    </div>

                    <div class="mb-4" id="passwordField">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                        <input type="password" id="userPassword" name="password"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                        <select id="userRole" name="role"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                            required>
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="kasir">Kasir</option>
                        </select>
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

    <!-- Change Password Modal -->
    <div id="passwordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Ubah Password</h3>
                <form id="passwordForm">
                    <input type="hidden" id="passwordUserId">

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Password Baru</label>
                        <input type="password" id="newPassword" name="password"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password</label>
                        <input type="password" id="confirmPassword" name="password_confirmation"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                            required>
                    </div>

                    <div class="flex justify-end gap-4">
                        <button type="button" id="closePasswordModal"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let isEdit = false;

        // Modal Controls
        document.getElementById('addUserBtn').addEventListener('click', openAddModal);
        document.getElementById('closeModal').addEventListener('click', closeModal);
        document.getElementById('closePasswordModal').addEventListener('click', closePasswordModal);

        // Form Submissions
        document.getElementById('userForm').addEventListener('submit', handleUserSubmit);
        document.getElementById('passwordForm').addEventListener('submit', handlePasswordSubmit);

        function openAddModal() {
            isEdit = false;
            document.getElementById('modalTitle').textContent = 'Tambah Pengguna';
            document.getElementById('userForm').reset();
            document.getElementById('userId').value = '';
            document.getElementById('passwordField').style.display = 'block';
            document.getElementById('userPassword').required = true;
            document.getElementById('userModal').classList.remove('hidden');
        }

        function editUser(id) {
            isEdit = true;
            document.getElementById('modalTitle').textContent = 'Edit Pengguna';
            document.getElementById('passwordField').style.display = 'none';
            document.getElementById('userPassword').required = false;

            fetch(`/admin/users/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.data;
                        document.getElementById('userId').value = user.id;
                        document.getElementById('userName').value = user.name;
                        document.getElementById('userEmail').value = user.email;
                        document.getElementById('userRole').value = user.role;
                        document.getElementById('userModal').classList.remove('hidden');
                    }
                });
        }

        function changePassword(id) {
            document.getElementById('passwordUserId').value = id;
            document.getElementById('passwordForm').reset();
            document.getElementById('passwordModal').classList.remove('hidden');
        }

        function deleteUser(id) {
            if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
                fetch(`/admin/users/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Pengguna berhasil dihapus');
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    });
            }
        }

        function handleUserSubmit(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);

            const url = isEdit ? `/admin/users/${data.userId}` : '/admin/users';
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
                        alert(isEdit ? 'Pengguna berhasil diupdate' : 'Pengguna berhasil ditambahkan');
                        closeModal();
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Something went wrong'));
                    }
                });
        }

        function handlePasswordSubmit(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);

            if (data.password !== data.password_confirmation) {
                alert('Password dan konfirmasi password tidak sama');
                return;
            }

            const userId = document.getElementById('passwordUserId').value;

            fetch(`/admin/users/${userId}/password`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Password berhasil diubah');
                        closePasswordModal();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
        }

        function closeModal() {
            document.getElementById('userModal').classList.add('hidden');
        }

        function closePasswordModal() {
            document.getElementById('passwordModal').classList.add('hidden');
        }

        // Role Filter
        document.getElementById('roleFilter').addEventListener('change', function() {
            const role = this.value;
            const rows = document.querySelectorAll('#usersTable tbody tr');

            rows.forEach(row => {
                if (role === '') {
                    row.style.display = '';
                } else {
                    const roleCell = row.cells[3];
                    if (roleCell && roleCell.textContent.toLowerCase().includes(role)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });
    </script>
</x-app-layout>
