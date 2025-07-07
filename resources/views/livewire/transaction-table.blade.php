<div class="p-4 space-y-4">

    {{-- 🔍 Filters --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <input type="text" wire:model.debounce.300ms="search" class="w-full border rounded px-3 py-2"
                placeholder="Search description or amount..." />
        </div>

        <div>
            <input type="date" wire:model="dateFrom" class="w-full border rounded px-3 py-2" />
        </div>

        <div>
            <input type="date" wire:model="dateTo" class="w-full border rounded px-3 py-2" />
        </div>

        <div class="col-span-1 md:col-span-2">
            <select wire:model="categories" multiple class="w-full border rounded px-3 py-2 h-32">
                @foreach ($allCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- 📊 Totals + Controls --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="text-sm text-gray-600">Total Amount:</span>
            <div class="text-lg font-semibold text-green-700">
                ₦{{ number_format($totalAmount, 2) }}
            </div>
        </div>

        <div class="flex items-center gap-2">
            <select wire:model="perPage" class="border rounded px-2 py-1">
                <option value="10">10 / page</option>
                <option value="25">25 / page</option>
                <option value="50">50 / page</option>
            </select>

            <button wire:click="exportCsv" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Export CSV
            </button>
        </div>
    </div>

    {{-- 📋 Table --}}
    <div class="overflow-x-auto bg-white border rounded shadow">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-3 cursor-pointer" wire:click="sortBy('date')">
                        Date @if ($sortField === 'date')
                            @if ($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @endif
                    </th>
                    <th class="p-3">Description</th>
                    <th class="p-3 cursor-pointer" wire:click="sortBy('amount')">
                        Amount @if ($sortField === 'amount')
                            @if ($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @endif
                    </th>
                    <th class="p-3">Category</th>
                    <th class="p-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3">{{ \Carbon\Carbon::parse($tx->date)->format('Y-m-d') }}</td>
                        <td class="p-3">{{ $tx->description }}</td>
                        <td class="p-3 text-green-600 font-medium">₦{{ number_format($tx->amount, 2) }}</td>
                        <td class="p-3">{{ $tx->category->name }}</td>
                        <td class="p-3">
                            <span
                                class="px-2 py-1 text-sm rounded-full
                                {{ $tx->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($tx->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">No transactions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 📄 Pagination --}}
    <div class="mt-4">
        {{ $transactions->links() }}
    </div>

</div>
