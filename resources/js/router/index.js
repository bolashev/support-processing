import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    { path: '/', redirect: '/incoming/new' },
    {
        path: '/incoming',
        component: () => import('../views/Incoming.vue'),
        children: [
            { path: '', redirect: '/incoming/new' },
            { path: 'new', component: () => import('../views/IncomingNew.vue') },
            { path: 'in-progress', component: () => import('../views/IncomingProgress.vue') },
            { path: 'shipped', component: () => import('../views/IncomingShipped.vue') },
        ],
    },
    { path: '/archive', component: () => import('../views/Archive.vue') },
    {
        path: '/statistics',
        component: () => import('../views/Statistics.vue'),
        children: [
            { path: '', redirect: '/statistics/sales' },
            { path: 'sales', component: () => import('../views/StatsSales.vue') },
            { path: 'support', component: () => import('../views/StatsSupport.vue') },
        ],
    },
    { path: '/notes', component: () => import('../views/Notes.vue') },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
