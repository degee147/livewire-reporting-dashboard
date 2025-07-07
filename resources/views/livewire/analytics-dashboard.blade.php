<div className="p-4 space-y-6">

    {{-- 🔍 Filters --}}
    <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="date" wire:model="dateFrom" className="border px-3 py-2 rounded" />
        <input type="date" wire:model="dateTo" className="border px-3 py-2 rounded" />
        <select multiple wire:model="categories" className="border px-3 py-2 rounded h-32">
            @foreach (\App\Models\Category::all() as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- 📊 Charts --}}
    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Bar Chart --}}
        <div className="bg-white border rounded p-4 shadow">
            <h2 className="text-lg font-bold mb-2">Monthly Spending</h2>
            <canvas id="barChart" />
        </div>

        {{-- Pie Chart --}}
        <div className="bg-white border rounded p-4 shadow">
            <h2 className="text-lg font-bold mb-2">Spending by Category</h2>
            <canvas id="pieChart" />
        </div>
    </div>

    {{-- Time Series --}}
    <div className="bg-white border rounded p-4 shadow">
        <h2 className="text-lg font-bold mb-2">Daily Transaction Volume</h2>
        <canvas id="lineChart" />
    </div>

    {{-- JS: Chart Rendering --}}
    <script>
        {
            `
        Livewire.hook('message.processed', () => {
            const barCtx = document.getElementById('barChart').getContext('2d');
            const pieCtx = document.getElementById('pieChart').getContext('2d');
            const lineCtx = document.getElementById('lineChart').getContext('2d');

            if (window.barChart) window.barChart.destroy();
            if (window.pieChart) window.pieChart.destroy();
            if (window.lineChart) window.lineChart.destroy();

            window.barChart = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: @json(collect($barChartData)->pluck('label')),
                    datasets: [{
                        label: '₦ Spent',
                        data: @json(collect($barChartData)->pluck('value')),
                        backgroundColor: '#3b82f6'
                    }]
                }
            });

            window.pieChart = new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: @json(collect($pieChartData)->pluck('label')),
                    datasets: [{
                        data: @json(collect($pieChartData)->pluck('value')),
                        backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#3b82f6', '#8b5cf6', '#f43f5e', '#22d3ee']
                    }]
                }
            });

            window.lineChart = new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: @json(collect($timeSeriesData)->pluck('date')),
                    datasets: [{
                        label: 'Transactions',
                        data: @json(collect($timeSeriesData)->pluck('value')),
                        fill: false,
                        borderColor: '#10b981',
                        tension: 0.3
                    }]
                }
            });
        });
    `
        }
    </script>

</div>
