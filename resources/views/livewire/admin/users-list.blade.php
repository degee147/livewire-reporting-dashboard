<div>
    {{-- In work, do what you enjoy. --}}
    <div class="p-6 bg-white rounded shadow">
        <div class="mb-4">
            <input type="text" wire:model.live="search" placeholder="Search users..."
                class="border rounded px-3 py-2 w-full md:w-1/3" />
        </div>

        <table class="w-full text-left border border-collapse">
            <thead>
                <tr class="bg-gray-100 text-sm text-gray-700">
                    <th class="p-2 border">Name</th>
                    <th class="p-2 border">Email</th>
                    <th class="p-2 border">Role</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-t">
                        <td class="p-2 border">{{ $user->name }}</td>
                        <td class="p-2 border">{{ $user->email }}</td>
                        <td class="p-2 border">
                            {{ $user->is_admin ? 'Admin' : 'User' }}
                        </td>
                        <td class="p-2 border">
                            <span
                                class="px-2 py-1 text-xs rounded {{ $user->active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $user->active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="p-2 border space-x-2">
                            <button wire:click="toggleStatus({{ $user->id }})"
                                class="px-2 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                Toggle
                            </button>

                            @if (auth()->id() !== $user->id)
                                <button wire:click="impersonate({{ $user->id }})"
                                    style="color: white; background: black;"
                                    class="px-2 py-1 text-xs bg-black text-white rounded hover:bg-green-700">
                                    Impersonate
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

</div>
