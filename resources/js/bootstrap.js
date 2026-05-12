import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = typeof localStorage !== 'undefined' ? localStorage.getItem('cakes_token') : null;
if (token) {
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}
