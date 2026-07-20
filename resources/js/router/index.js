import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    { path: '/', redirect: '/incoming' },
    {
        path: '/incoming',
        name: 'incoming',
        component: () => import('../pages/IncomingPage.vue'),
        meta: { title: 'Входящие заказы' },
    },
    {
        path: '/archive',
        name: 'archive',
        component: () => import('../pages/ArchivePage.vue'),
        meta: { title: 'Архив' },
    },
    {
        path: '/statistics',
        component: () => import('../pages/StatisticsPage.vue'),
        meta: { title: 'Статистика' },
        redirect: { name: 'stats-sales' },
        children: [
            {
                path: 'sales',
                name: 'stats-sales',
                component: () => import('../pages/StatsSalesPage.vue'),
            },
            {
                path: 'support',
                name: 'stats-support',
                component: () => import('../pages/StatsSupportPage.vue'),
            },
        ],
    },
    {
        path: '/notes',
        name: 'notes',
        component: () => import('../pages/NotesPage.vue'),
        meta: { title: 'Заметки' },
    },
    {
        path: '/orders/:id',
        name: 'order',
        component: () => import('../pages/OrderModalPage.vue'),
        meta: { title: 'Заказ' },
    },
    { path: '/:pathMatch(.*)*', redirect: '/incoming' },
]

function stringifyQuery(query) {
    const params = new URLSearchParams()
    for (const [key, value] of Object.entries(query)) {
        if (value === null || value === undefined) continue
        if (Array.isArray(value)) {
            value.forEach(v => params.append(key, v))
        } else {
            params.set(key, value)
        }
    }
    return params.toString()
}

const router = createRouter({
    history: createWebHistory(),
    routes,
    stringifyQuery,
})

export default router
