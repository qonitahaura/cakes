import api from '../api';
import { guard } from '../auth';

export default async function init() {
    await guard('customer_service');
    const { data } = await api.get('/cs/orders/pickup-schedule');
    const root = document.getElementById('cs-pickup');
    root.innerHTML = Object.entries(data)
        .map(
            ([date, orders]) => `<div class="card">
        <h3 class="mb-3 font-semibold text-accent-900">${date}</h3>
        <ul class="space-y-2 text-sm">
          ${orders
              .map(
                  (o) => `<li class="flex justify-between gap-2 rounded-lg bg-accent-50/80 px-3 py-2">
            <span class="font-mono text-xs">${o.code}</span>
            <span>${o.user?.name || '—'}</span>
            <span class="text-accent-500">${o.pickup_time || ''}</span>
          </li>`
              )
              .join('')}
        </ul>
      </div>`
        )
        .join('');
}
