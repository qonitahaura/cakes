import api from '../api';
import { guard } from '../auth';
import { revenueLineChart, statusDoughnutChart } from '../charts';

export default async function init() {
    await guard('admin');

    const [{ data: summary }, { data: revenue }, { data: orders }] = await Promise.all([
        api.get('/admin/reports/summary'),
        api.get('/admin/reports/revenue', { params: { days: 30 } }),
        api.get('/admin/orders'),
    ]);

    document.getElementById('stat-users').textContent = summary.total_users;
    document.getElementById('stat-products').textContent = summary.total_products;
    document.getElementById('stat-orders').textContent = summary.total_orders;
    document.getElementById('stat-revenue').textContent = Number(summary.total_revenue).toLocaleString(undefined, {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    });
    document.getElementById('stat-pending').textContent = summary.pending_orders;

    const labels = revenue.map((r) => r.d);
    const values = revenue.map((r) => Number(r.revenue));
    const lineEl = document.getElementById('chart-revenue');
    if (lineEl && labels.length) revenueLineChart(lineEl, labels, values);

    const ob = summary.orders_by_status || {};
    const dLabels = Object.keys(ob);
    const dVals = Object.values(ob).map((v) => Number(v));
    const doughEl = document.getElementById('chart-status');
    const sumD = dVals.reduce((a, b) => a + b, 0);
    if (doughEl && dLabels.length && sumD > 0) statusDoughnutChart(doughEl, dLabels, dVals);

    const tbody = document.querySelector('#tbl-recent-orders tbody');
    if (tbody) {
        tbody.innerHTML = orders
            .slice(0, 8)
            .map(
                (o) => `<tr class="border-t border-accent-100">
        <td class="px-4 py-2 font-mono text-xs">${o.code}</td>
        <td class="px-4 py-2">${o.user?.name || '—'}</td>
        <td class="px-4 py-2">${Number(o.total_price).toLocaleString()}</td>
        <td class="px-4 py-2"><span class="rounded-full bg-primary-100 px-2 py-0.5 text-xs font-medium text-primary-800">${o.status}</span></td>
        <td class="px-4 py-2 text-right"><a class="text-primary-600 hover:underline" href="/admin/orders/${o.id}">View</a></td>
      </tr>`
            )
            .join('');
    }
}
