import {
    Chart,
    LineController,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
    Title,
    Tooltip,
    Legend,
    Filler,
    ArcElement,
    DoughnutController,
} from 'chart.js';

Chart.register(
    LineController,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
    Title,
    Tooltip,
    Legend,
    Filler,
    ArcElement,
    DoughnutController
);

export function revenueLineChart(canvas, labels, values) {
    return new Chart(canvas.getContext('2d'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Revenue',
                    data: values,
                    borderColor: 'rgb(234 88 12)',
                    backgroundColor: 'rgba(234, 88, 12, 0.1)',
                    fill: true,
                    tension: 0.35,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true },
            },
        },
    });
}

export function statusDoughnutChart(canvas, labels, values) {
    return new Chart(canvas.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels,
            datasets: [
                {
                    data: values,
                    backgroundColor: [
                        '#fbbf24',
                        '#fb923c',
                        '#34d399',
                        '#38bdf8',
                        '#a78bfa',
                        '#4ade80',
                        '#f87171',
                        '#94a3b8',
                    ],
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
        },
    });
}
