<div>
    <h2 class="font-semibold mb-2">{{ $title }}</h2>
    <canvas x-data x-init="new Chart($el, {
        type: 'line',
        data: {
            labels: @js($labels),
            datasets: [{
                label: '{{ $title }}',
                data: @js($data),
                fill: true,
                borderColor: 'rgba(59, 130, 246, 1)', // Solid line
                backgroundColor: 'rgba(59, 130, 246, 0.2)', // Area under the line
                pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                tension: 0.3 // Curved lines
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: { enabled: true },
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });"></canvas>
</div>
