import api from '../api';
import { guard } from '../auth';

export default async function init() {
    await guard('customer_service');
    const [inc, pay] = await Promise.all([api.get('/cs/orders/incoming'), api.get('/cs/payments')]);

    // API returns paginated response shape: { data: [...], meta: {...} }
    const incomingRows = inc.data?.data ?? inc.data ?? [];
    const paymentRows = pay.data?.data ?? pay.data ?? [];

    document.getElementById('cs-incoming-count').textContent = incomingRows.length;
    document.getElementById('cs-unpaid').textContent = paymentRows.filter((p) => p.payment_status === 'unpaid').length;
    document.getElementById('cs-paid').textContent = paymentRows.filter((p) => p.payment_status === 'paid').length;

}

