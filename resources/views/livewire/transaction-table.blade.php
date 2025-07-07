<div>
    <div class="p-4 space-y-4">

        {{-- 🔍 Filters --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <input type="text" wire:model.live.debounce.300ms="search" class="w-full border rounded px-3 py-2"
                    placeholder="Search description or amount..." />
            </div>

            <div>
                <input type="date" wire:model.live="dateFrom" class="w-full border rounded px-3 py-2" />
            </div>

            <div>
                <input type="date" wire:model.live="dateTo" class="w-full border rounded px-3 py-2" />
            </div>

            {{-- Custom Multi-Select Dropdown --}}
            <div class="col-span-1 md:col-span-2 relative" x-data="{
                open: false,
                selectedCategories: @entangle('categories').live,
                allCategories: @js($allCategories),

                get selectedText() {
                    if (this.selectedCategories.length === 0) {
                        return 'All Categories';
                    }
                    if (this.selectedCategories.length === 1) {
                        const category = this.allCategories.find(c => c.id == this.selectedCategories[0]);
                        return category ? category.name : 'Select Categories';
                    }
                    return `${this.selectedCategories.length} categories selected`;
                },

                toggleCategory(categoryId) {
                    if (this.selectedCategories.includes(categoryId)) {
                        this.selectedCategories = this.selectedCategories.filter(id => id !== categoryId);
                    } else {
                        this.selectedCategories = [...this.selectedCategories, categoryId];
                    }
                },

                selectAll() {
                    this.selectedCategories = [];
                },

                isSelected(categoryId) {
                    return this.selectedCategories.includes(categoryId);
                }
            }" @click.away="open = false">

                <!-- Dropdown Button -->
                <button @click="open = !open" type="button"
                    class="w-full border rounded px-3 py-2 bg-white text-left flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'ring-2 ring-blue-500 border-blue-500': open }">
                    <span class="block truncate" x-text="selectedText"></span>
                    <svg class="ml-2 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto"
                    style="display: none;">
                    <!-- All Categories Option -->
                    <div @click="selectAll()"
                        class="px-3 py-2 hover:bg-gray-100 cursor-pointer flex items-center border-b border-gray-200">
                        <input type="checkbox" :checked="selectedCategories.length === 0"
                            class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" readonly>
                        <span class="text-sm font-medium text-gray-700">All Categories</span>
                    </div>

                    <!-- Individual Categories -->
                    <template x-for="category in allCategories" :key="category.id">
                        <div @click="toggleCategory(category.id)"
                            class="px-3 py-2 hover:bg-gray-100 cursor-pointer flex items-center">
                            <input type="checkbox" :checked="isSelected(category.id)"
                                class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" readonly>
                            <span class="text-sm text-gray-700" x-text="category.name"></span>
                        </div>
                    </template>

                    <!-- No categories message -->
                    <div x-show="allCategories.length === 0" class="px-3 py-2 text-sm text-gray-500">
                        No categories available
                    </div>
                </div>
            </div>
        </div>

        {{-- 📊 Totals + Controls --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-4">
                <div>
                    <span class="text-sm text-gray-600">Total Amount:</span>
                    <div class="text-lg font-semibold text-green-700">
                        ₦{{ number_format($totalAmount, 2) }}
                    </div>
                </div>

                {{-- Clear Filters Button --}}
                <button wire:click="clearFilters" class="text-sm text-blue-600 hover:text-blue-800 underline">
                    Clear Filters
                </button>
            </div>

            <div class="flex items-center gap-2">
                <select wire:model.live="perPage" class="border rounded px-2 py-1">
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
        <div class="mt-6 overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200 text-sm">

                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="cursor-pointer px-4 py-2" wire:click="sortBy('date')">
                            Date @if ($sortField === 'date')
                                {!! $sortDirection === 'asc' ? '↑' : '↓' !!}
                            @endif
                        </th>
                        <th class="cursor-pointer px-4 py-2" wire:click="sortBy('category_id')">
                            Category @if ($sortField === 'category_id')
                                {!! $sortDirection === 'asc' ? '↑' : '↓' !!}
                            @endif
                        </th>
                        <th class="cursor-pointer px-4 py-2" wire:click="sortBy('description')">
                            Description @if ($sortField === 'description')
                                {!! $sortDirection === 'asc' ? '↑' : '↓' !!}
                            @endif
                        </th>
                        <th class="cursor-pointer px-4 py-2" wire:click="sortBy('amount')">
                            Amount (₦) @if ($sortField === 'amount')
                                {!! $sortDirection === 'asc' ? '↑' : '↓' !!}
                            @endif
                        </th>
                        <th class="cursor-pointer px-4 py-2" wire:click="sortBy('status')">
                            Status @if ($sortField === 'status')
                                {!! $sortDirection === 'asc' ? '↑' : '↓' !!}
                            @endif
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($transactions as $tx)
                        <tr>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($tx->date)->format('Y-m-d') }}</td>
                            <td class="px-4 py-2">{{ $tx->category->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $tx->description }}</td>
                            <td class="px-4 py-2">₦{{ number_format($tx->amount, 2) }}</td>
                            <td class="px-4 py-2">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded {{ $tx->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($tx->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center px-4 py-6 text-gray-500">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
