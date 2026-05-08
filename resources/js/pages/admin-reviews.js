import api from '../api';
import { guard } from '../auth';

export default async function init() {
    await guard('admin');
    const { data } = await api.get('/admin/reviews');
    const tbody = document.querySelector('#tbl-reviews tbody');
    tbody.innerHTML = data
        .map(
            (r) => `<tr class="border-t border-accent-100">
      <td class="px-4 py-2">${r.id}</td>
      <td class="px-4 py-2">${r.product?.name || r.product_id}</td>
      <td class="px-4 py-2">${r.user?.name || '—'}</td>
      <td class="px-4 py-2">${r.rating} ★</td>
      <td class="px-4 py-2 max-w-md truncate">${r.comment || ''}</td>
      <td class="px-4 py-2 text-right">
        <button data-del="${r.id}" class="text-red-600 hover:underline text-sm">Remove</button>
      </td>
    </tr>`
        )
        .join('');

    document.querySelector('#tbl-reviews')?.addEventListener('click', async (e) => {
        const t = e.target;
        if (t.matches('[data-del]')) {
            if (!confirm('Remove this review?')) return;
            await api.delete(`/admin/reviews/${t.dataset.del}`);
            window.CakesAuth.toast('Review removed', 'success');
            window.location.reload();
        }
    });
}
