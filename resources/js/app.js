import './bootstrap';
import Alpine from 'alpinejs';
import api from './api';
import * as Auth from './auth';

document.addEventListener('alpine:init', () => {
    Alpine.store('layout', { mobileOpen: false });

    Alpine.store('toast', {
        items: [],
        push(message, type = 'success') {
            const id = Date.now() + Math.random();
            this.items.push({ id, message, type });
            setTimeout(() => {
                this.items = this.items.filter((i) => i.id !== id);
            }, 4500);
        },
    });
});

window.Alpine = Alpine;
Alpine.start();

window.api = api;
window.CakesAuth = {
    ...Auth,
    toast: (msg, type) => Alpine.store('toast').push(msg, type),
};

const page = document.body?.dataset?.page;

const loaders = {
    login: () => import('./pages/login.js').then((m) => m.default()),
    'admin-dashboard': () => import('./pages/admin-dashboard.js').then((m) => m.default()),
    'admin-users': () => import('./pages/admin-users.js').then((m) => m.default()),
    'admin-categories': () => import('./pages/admin-categories.js').then((m) => m.default()),
    'admin-products': () => import('./pages/admin-products.js').then((m) => m.default()),
    'admin-customizations': () => import('./pages/admin-customizations.js').then((m) => m.default()),
    'admin-orders': () => import('./pages/admin-orders.js').then((m) => m.default()),
    'admin-orders-show': () => import('./pages/admin-orders-show.js').then((m) => m.default()),
    'admin-payments': () => import('./pages/admin-payments.js').then((m) => m.default()),
    'admin-reports': () => import('./pages/admin-reports.js').then((m) => m.default()),
    'admin-reviews': () => import('./pages/admin-reviews.js').then((m) => m.default()),
    'baker-dashboard': () => import('./pages/baker-dashboard.js').then((m) => m.default()),
    'baker-orders': () => import('./pages/baker-orders.js').then((m) => m.default()),
    'baker-schedule': () => import('./pages/baker-schedule.js').then((m) => m.default()),
    'baker-completed': () => import('./pages/baker-completed.js').then((m) => m.default()),
    'cs-dashboard': () => import('./pages/cs-dashboard.js').then((m) => m.default()),
    'cs-incoming': () => import('./pages/cs-incoming.js').then((m) => m.default()),
    'cs-validation': () => import('./pages/cs-validation.js').then((m) => m.default()),
    'cs-payments': () => import('./pages/cs-payments.js').then((m) => m.default()),
    'cs-pickup': () => import('./pages/cs-pickup.js').then((m) => m.default()),
    'cs-history': () => import('./pages/cs-history.js').then((m) => m.default()),
};

if (page && loaders[page]) {
    loaders[page]().catch((e) => console.error(e));
}
