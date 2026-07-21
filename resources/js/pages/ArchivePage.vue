<template>
    <MainLayout>
        <template #filter>
            <ArchiveFilter />
        </template>

        <div class="section-block archive-page">
            <div class="section-title-row">
                <span class="section-title">Архив заказов</span>
                <span class="section-title-sep">·</span>
                <span class="section-title-count">{{ archiveStore.pagination.total || archiveStore.items.length }}</span>
            </div>

            <div v-if="archiveStore.loading && !archiveStore.items.length" class="archive-empty">
                <span>Загрузка</span>
            </div>
            <template v-else-if="archiveStore.items.length">
                <StatsTable
                    :columns="columns"
                    :sort-key="sortKey"
                    :sort-direction="sortDirection"
                    @sort="toggleSort"
                >
                    <tr v-for="order in archiveStore.items" :key="order.id">
                        <td>
                            <span class="archive-status-badge">{{ order.order_status_label }}</span>
                        </td>
                        <td>Заказ № {{ order.number }}</td>
                        <td>{{ order.processing_time || '—' }}</td>
                        <td>{{ formatDateTime(order.shipped_at) }}</td>
                        <td>
                            <div class="archive-manager">
                                <Icon name="user" :size="16" color="#282828" />
                                <span>{{ order.manager_name || 'Не назначен' }}</span>
                            </div>
                        </td>
                    </tr>
                </StatsTable>

                <div class="archive-sentinel">
                    <span v-if="archiveStore.loadingMore" class="archive-loading-more">Загрузка...</span>
                    <span v-else-if="!hasMore" class="archive-end">Все заказы загружены</span>
                </div>
            </template>

            <div v-else class="archive-empty">
                <span v-if="archiveStore.hasAny">Ничего не найдено</span>
                <span v-else>В архиве пока пусто</span>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useArchiveStore } from '@/stores/archive'
import MainLayout from '@/components/layout/MainLayout.vue'
import ArchiveFilter from '@/components/archive/ArchiveFilter.vue'
import Icon from '@/components/ui/Icon.vue'
import StatsTable from '@/components/ui/StatsTable.vue'

const archiveStore = useArchiveStore()
const route = useRoute()
const router = useRouter()

const columns = [
    { key: 'order_status_label', label: 'Статус', width: '180px' },
    { key: 'number', label: 'Номер заказа', width: '180px' },
    { key: 'processing_time', label: 'Время обработки', width: '180px', sortable: true },
    { key: 'shipped_at', label: 'Дата и время', width: '180px', sortable: true },
    { key: 'manager_name', label: 'Менеджер заказа', width: '220px' },
]

const sortKey = computed(() => route.query.sort || null)
const sortDirection = computed(() => route.query.dir || 'desc')

function toggleSort(key) {
    const query = { ...route.query }
    if (sortKey.value === key) {
        if (sortDirection.value === 'asc') {
            query.sort = key
            query.dir = 'desc'
        } else {
            delete query.sort
            delete query.dir
        }
    } else {
        query.sort = key
        query.dir = 'asc'
    }
    router.replace({ query })
}

const hasMore = computed(() => archiveStore.pagination.page < archiveStore.pagination.lastPage)

let ticking = false

function onScroll() {
    if (ticking) return
    ticking = true
    requestAnimationFrame(() => {
        ticking = false
        if (archiveStore.loading || archiveStore.loadingMore || !hasMore.value) return
        const distance = document.documentElement.scrollHeight - (window.scrollY + window.innerHeight)
        if (distance < 300) {
            archiveStore.fetchArchive(archiveStore.lastParams, { append: true })
        }
    })
}

onMounted(() => {
    window.addEventListener('scroll', onScroll, { passive: true })
    onScroll()
})

onBeforeUnmount(() => {
    window.removeEventListener('scroll', onScroll)
})

function formatDateTime(dateStr) {
    if (!dateStr) return '—'
    const d = new Date(dateStr)
    return d.toLocaleDateString('ru-RU', { day: 'numeric', month: 'long' })
        + ' · ' + d.toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' })
}
</script>
