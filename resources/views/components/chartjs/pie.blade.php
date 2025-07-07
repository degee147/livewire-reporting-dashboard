@php
    // Generate a set of distinct background colors for each label
    $colors = collect([
        '#3b82f6', // blue
        '#10b981', // green
        '#f59e0b', // amber
        '#ef4444', // red
        '#8b5cf6', // violet
        '#ec4899', // pink
        '#14b8a6', // teal
        '#f43f5e', // rose
        '#6366f1', // indigo
        '#84cc16', // lime
        '#eab308', // yellow
        '#a855f7', // purple
    ])
        ->take(count($labels))
        ->values();
@endphp

<div>
    <h2 class="font-semibold mb-2">{{ $title }}</h2>
    <canvas x-data x-init="new Chart($el, {
        type: 'pie',
        data: {
            labels: @js($labels),
            datasets: [{
                label: '{{ $title }}',
                data: @js($data),
                backgroundColor: @js($colors),
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: { enabled: true },
                legend: { display: true, position: 'bottom' }
            }
        }
    });"></canvas>
</div>
