<div>
    {{-- In work, do what you enjoy. --}}
    <div class="p-6 bg-white rounded shadow">
        <div class="mb-4 flex justify-between items-center">
            <input type="text" wire:model.live.debounce.300ms="search" class="border px-3 py-2 rounded w-1/2"
                placeholder="Search users by name or email..." />

            <select wire:model.live="perPage" class="border px-2 py-1 rounded">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
            </select>
        </div>

        <table class="w-full table-auto border-collapse border border-gray-300 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2 text-left">Name</th>
                    <th class="border px-4 py-2 text-left">Email</th>
                    <th class="border px-4 py-2 text-left">Role</th>
                    <th class="border px-4 py-2 text-left">Registered</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td class="border px-4 py-2">{{ $user->name }}</td>
                        <td class="border px-4 py-2">{{ $user->email }}</td>
                        <td class="border px-4 py-2">{{ $user->is_admin ? 'Admin' : 'User' }}</td>
                        <td class="border px-4 py-2">{{ $user->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="border px-4 py-2 text-center text-gray-500">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

</div>
