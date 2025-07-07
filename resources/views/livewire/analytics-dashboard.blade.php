<div class="space-y-6">


    {{-- Charts --}}
    {{-- 📊 Analytics Charts Container --}}
    <div class="bg-white p-6 rounded-xl shadow-md space-y-6">

        {{-- Filters --}}
        {{-- 📊 Analytics Filters --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <!-- From Date -->
            <div>
                <input type="date" wire:model.live="dateFrom" class="w-full border rounded px-3 py-2"
                    placeholder="From date" />
            </div>

            <!-- To Date -->
            <div>
                <input type="date" wire:model.live="dateTo" class="w-full border rounded px-3 py-2"
                    placeholder="To date" />
            </div>

            <!-- Custom Category Multi-Select -->
            <div class="col-span-1 md:col-span-3 relative" x-data="{
                open: false,
                selectedCategories: @entangle('categories').live,
                allCategories: @js($allCategories),

                get selectedText() {
                    if (this.selectedCategories.length === 0) return 'All Categories';
                    if (this.selectedCategories.length === 1) {
                        const cat = this.allCategories.find(c => c.id == this.selectedCategories[0]);
                        return cat ? cat.name : 'Select Categories';
                    }
                    return `${this.selectedCategories.length} categories selected`;
                },

                toggleCategory(id) {
                    this.selectedCategories.includes(id) ?
                        this.selectedCategories = this.selectedCategories.filter(x => x !== id) :
                        this.selectedCategories.push(id);
                },

                selectAll() {
                    this.selectedCategories = [];
                },

                isSelected(id) {
                    return this.selectedCategories.includes(id);
                }
            }" @click.away="open = false">

                <!-- Button -->
                <button @click="open = !open" type="button"
                    class="w-full border rounded px-3 py-2 bg-white text-left flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'ring-2 ring-blue-500 border-blue-500': open }">
                    <span class="block truncate" x-text="selectedText"></span>
                    <svg class="ml-2 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown -->
                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                    x-transition:leave="transition ease-in duration-75"
                    class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto"
                    style="display: none;">

                    <!-- All Option -->
                    <div @click="selectAll()"
                        class="px-3 py-2 hover:bg-gray-100 cursor-pointer flex items-center border-b border-gray-200">
                        <input type="checkbox" :checked="selectedCategories.length === 0"
                            class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" readonly>
                        <span class="text-sm font-medium text-gray-700">All Categories</span>
                    </div>

                    <!-- Dynamic Category List -->
                    <template x-for="category in allCategories" :key="category.id">
                        <div @click="toggleCategory(category.id)"
                            class="px-3 py-2 hover:bg-gray-100 cursor-pointer flex items-center">
                            <input type="checkbox" :checked="isSelected(category.id)"
                                class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" readonly>
                            <span class="text-sm text-gray-700" x-text="category.name"></span>
                        </div>
                    </template>

                    <div x-show="allCategories.length === 0" class="px-3 py-2 text-sm text-gray-500">
                        No categories available
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-chartjs.bar title="Monthly Spending (₦)" :labels="$monthly->keys()" :data="$monthly->values()" />
            <x-chartjs.pie title="Spending by Category" :labels="$category->keys()" :data="$category->values()" />
        </div>

        {{-- Line Chart --}}
        <div>
            <x-chartjs.line title="Daily Transaction Volume" :labels="$daily->keys()" :data="$daily->values()" />
        </div>
    </div>
</div>
