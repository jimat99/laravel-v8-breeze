<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">User Management</h2>
    </x-slot>

    <div class="p-6">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-700 dark:bg-green-100 dark:text-green-800" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 p-4 text-red-700 dark:bg-red-100 dark:text-red-800" role="alert">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-4 flex justify-between items-center">
            <!-- Search -->
            <form method="GET">
                <input 
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search name or email" 
                    class="border p-2" 
                >

                <select name="role" class="rounded-xl pr-8">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>
                        User
                    </option>
                </select>

                <select name="status" class="rounded-xl pr-8">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="deleted" {{ request('status') === 'deleted' ? 'selected' : '' }}>
                        Deleted
                    </option>
                </select>

                <button class="bg-green-500 text-white px-4 py-2 border-0 rounded cursor-pointer hover:bg-green-600">
                    Search
                </button>
            </form>

            <!-- Audit Logs -->
            <a href="{{ route('admin.audit-logs.index') }}" 
               class="bg-green-500 text-white px-4 py-2 border-0 rounded cursor-pointer hover:bg-green-600">
                View Audit Logs
            </a>
        </div>

        <table class="w-full border border-gray-400 border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-1 border border-gray-400">Name</th>
                    <th class="p-1 border border-gray-400">Email</th>
                    <th class="p-1 border border-gray-400">Role</th>
                    <th class="p-1 border-t border-gray-400">Action</th>
                </tr>
            </thead>
            
            <tbody>
                @foreach($users as $user)
                    <tr class="border-t">
                        <td class="p-1 border border-gray-400">{{ $user->name }}</td>
                        <td class="p-1 border border-gray-400">{{ $user->email }}</td>
                        <td class="p-1 border border-gray-400">{{ $user->role }}</td>
                        <td class="border-t border-gray-400 justify-center flex gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:underline">
                                Edit
                            </a>

                            |

                            @if($user->trashed())
                                <form method="POST" action="{{ route('admin.users.restore', $user->id) }}" class="restore-form">
                                    @csrf

                                    <button type="button" class="restore-btn text-blue-700 hover:underline">
                                        Restore
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="delete-form">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" class="delete-btn text-red-600 hover:underline">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </td>     
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                confirmAction({
                    title: 'Delete this user?',
                    text: 'This can be undone by restoring the user.',
                    icon: 'warning',
                    confirmText: 'Yes, delete',
                    confirmColor: '#dc2626',
                    form: this.closest('form'),
                });
            });
        });

        document.querySelectorAll('.restore-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                confirmAction({
                    title: 'Restore this user?',
                    text: 'This will restore the deleted user.',
                    icon: 'question',
                    confirmText: 'Yes, restore',
                    confirmColor: '#16a34a',
                    form: this.closest('form'),
                });
            });
        });
    });
</script>


















{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form');

                Swal.fire({
                    title: 'Delete this user?',
                    text: 'This action can be undone by restoring the user.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        document.querySelectorAll('.restore-btn').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form');

                Swal.fire({
                    title: 'Restore this user?',
                    text: 'This action will restore the deleted user.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    confirmButtonText: 'Yes, restore',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

    });
</script>
 --}}