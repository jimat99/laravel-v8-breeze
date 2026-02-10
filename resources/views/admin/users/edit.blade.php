<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Edit User
        </h2>
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
        
        <form method="POST"
              action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="text-xl font-bold">Name</label>
                <input value="{{ $user->name }}" disabled class="w-full p-2 border border-gray-300">
            </div>

            <div class="mb-4">
                <label class="text-xl font-bold">Role</label>
                <select name="role" class="w-full">
                    <option value="user"
                        {{ $user->role === 'user' ? 'selected' : '' }}>
                        User
                    </option>
                    <option value="admin"
                        {{ $user->role === 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>
                </select>
            </div>

            <button style="background: #4CAF50; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;">
                Save
            </button>
        </form>
    </div>
</x-app-layout>
