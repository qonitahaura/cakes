import api from './api';

const TOKEN_KEY = 'cakes_token';
const USER_KEY = 'cakes_user';

export function getToken() {
    return localStorage.getItem(TOKEN_KEY);
}

export function getStoredUser() {
    try {
        const raw = localStorage.getItem(USER_KEY);
        return raw ? JSON.parse(raw) : null;
    } catch {
        return null;
    }
}

export function setSession(token, user) {
    localStorage.setItem(TOKEN_KEY, token);
    localStorage.setItem(USER_KEY, JSON.stringify(user));
}

export function clearSession() {
    localStorage.removeItem(TOKEN_KEY);
    localStorage.removeItem(USER_KEY);
}

export function hasRole(user, roleName) {
    if (!user?.roles) return false;
    const roles = Array.isArray(user.roles) ? user.roles : [...user.roles];
    return roles.includes(roleName);
}

export async function login(email, password) {
    const { data } = await api.post('/auth/login', { email, password });
    if (data.token && data.user) {
        setSession(data.token, data.user);
    }
    return data;
}

export async function logout() {
    try {
        await api.post('/logout');
    } catch {
        // ignore
    }
    clearSession();
    window.location.href = '/login';
}

export async function fetchProfile() {
    const { data } = await api.get('/profile');
    return data;
}

/**
 * @param {'admin'|'baker'|'customer_service'} roleRequired
 */
export async function guard(roleRequired) {
    const token = getToken();
    if (!token) {
        window.location.href = '/login';
        return null;
    }
    let user;
    try {
        user = await fetchProfile();
        const roles = user?.roles ?? [];
        setSession(token, {
            id: user.id,
            name: user.name,
            email: user.email,
            roles: Array.isArray(roles) ? roles : [...roles],
        });
    } catch {
        window.location.href = '/login';
        return null;
    }
    const roleList = Array.isArray(user.roles) ? user.roles : [...(user.roles || [])];
    if (!roleList.includes(roleRequired)) {
        window.location.href = '/login';
        return null;
    }
    return user;
}
