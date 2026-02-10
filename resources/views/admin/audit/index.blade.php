<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Audit Logs</h2>
    </x-slot>

    <div class="p-6">
        <div class="mb-4 flex justify-between items-center">
            <form method="GET" class="mb-4 flex flex-col sm:flex-row gap-2 items-end">
                <!-- Search -->
                <div class="w-full sm:w-auto">
                    <label class="block text-sm text-gray-600 mb-1">Search</label>
                    <input 
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Admin, action, name, email"
                        class="w-full sm:w-auto border p-2"
                    >
                </div>

                <!-- Date From -->
                <div class="w-full sm:w-auto">
                    <label class="block text-sm text-gray-600 mb-1">From</label>
                    <input
                        type="date"
                        name="from"
                        value="{{ request('from') }}"
                        class="w-full sm:w-auto rounded-lg border p-2"
                    >
                </div>

                <!-- Date To -->
                <div class="w-full sm:w-auto">
                    <label class="block text-sm text-gray-600 mb-1">To</label>
                    <input
                        type="date"
                        name="to"
                        value="{{ request('to') }}"
                        class="w-full sm:w-auto rounded-lg border p-2"
                    >
                </div>

                <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Search
                </button>
            </form>

            <!-- Export Excel -->
            <a href="{{ route('admin.audit-logs.export-excel', request()->query()) }}" 
               class="bg-green-500 text-white px-4 py-2 border-0 rounded cursor-pointer hover:bg-green-600">
                Export Excel
            </a>
        </div>

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-1 border border-gray-400" rowspan="2">Admin</th>
                    <th class="p-1 border border-gray-400" rowspan="2">Action</th>
                    <th class="p-1 border border-gray-400" colspan="2">User</th>
                    <th class="p-1 border border-gray-400" rowspan="2">Date</th>
                </tr>
                
                <tr class="bg-gray-100">
                    <th class="p-1 border border-gray-400">Name</th>
                    <th class="p-1 border border-gray-400">Email</th>
                </tr>
            </thead>

            <tbody>
                @foreach($logs as $log)
                    <tr class="border-t">
                        <td class="p-1 border border-gray-400">{{ $log->admin->name }}</td>
                        <td class="p-1 border border-gray-400">{{ $log->action }}</td>
                        <td class="p-1 border border-gray-400">{{ $log->target->name }}</td>
                        <td class="p-1 border border-gray-400">{{ $log->target->email }}</td>
                        <td class="p-1 border border-gray-400">{{ $log->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $logs->links() }}
        </div>
    </div>
</x-app-layout>






 <!-- Export CSV -->
            {{-- <a href="{{ route('admin.audit-logs.export', request()->query()) }}" 
               class="bg-green-500 text-white px-4 py-2 border-0 rounded cursor-pointer hover:bg-green-600">
                Export CSV
            </a> --}}
