import { login as apiLogin, clearSession, getStoredUser } from '../auth';

export default function initLogin() {
    const form = document.getElementById('cakes-login-form');
    const err = document.getElementById('cakes-login-error');
    if (!form) return;

    const token = localStorage.getItem('cakes_token');
    if (token) {
        const u = getStoredUser();
        const roles = u?.roles || [];
        if (roles.includes('admin')) window.location.href = '/admin/dashboard';
        else if (roles.includes('baker')) window.location.href = '/baker/dashboard';
        else if (roles.includes('customer_service')) window.location.href = '/cs/dashboard';
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        err.classList.add('hidden');
        const fd = new FormData(form);
        const email = String(fd.get('email') || '');
        const password = String(fd.get('password') || '');
        try {
            const data = await apiLogin(email, password);
            const roles = data.user?.roles || [];
            if (roles.includes('admin')) window.location.href = '/admin/dashboard';
            else if (roles.includes('baker')) window.location.href = '/baker/dashboard';
            else if (roles.includes('customer_service')) window.location.href = '/cs/dashboard';
            else {
                clearSession();
                err.textContent = 'This portal is for staff only.';
                err.classList.remove('hidden');
            }
        } catch (error) {
            err.textContent = error.response?.data?.message || 'Login failed';
            err.classList.remove('hidden');
        }
    });
}
