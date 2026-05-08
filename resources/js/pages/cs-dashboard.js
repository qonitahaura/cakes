import api from '../api';
import { guard } from '../auth';

export default async function init() {
    await guard('customer_service');
    const [inc, pay] = await Promise.all([api.get('/cs/orders/incoming'), api.get('/cs/payments')]);
    document.getElementById('cs-incoming-count').textContent = inc.data.length;
    document.getElementById('cs-unpaid').textContent = pay.data.filter((p) => p.payment_status === 'unpaid').length;
    document.getElementById('cs-paid').textContent = pay.data.filter((p) => p.payment_status === 'paid').length;
}
