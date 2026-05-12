import api from '../api';
import { guard } from '../auth';
import { revenueLineChart } from '../charts';

export default async function init() {
    await guard('admin');
    const [{ data: summary }, { data: revenue }] = await Promise.all([
        api.get('/admin/reports/summary'),
        api.get('/admin/reports/revenue', { params: { days: 90 } }),
    ]);

    document.getElementById('rep-summary').innerHTML = `
      <p>Total revenue: <strong>${Number(summary.total_revenue).toLocaleString()}</strong></p>
      <p>Orders (all): <strong>${summary.total_orders}</strong></p>
    `;

    const labels = revenue.map((r) => r.d);
    const values = revenue.map((r) => Number(r.revenue));
    const el = document.getElementById('chart-reports');
    if (el && labels.length) revenueLineChart(el, labels, values);

    document.getElementById('btn-export-csv')?.addEventListener('click', async () => {
        const start = document.getElementById('exp-start').value;
        const end = document.getElementById('exp-end').value;
        const res = await api.get('/admin/reports/export/csv', {
            params: { start, end },
            responseType: 'blob',
        });
        const url = URL.createObjectURL(res.data);
        const a = document.createElement('a');
        a.href = url;
        a.download = `orders-${start}-to-${end}.csv`;
        a.click();
        URL.revokeObjectURL(url);
        window.CakesAuth.toast('Download started', 'success');
    });
}
