<template>
    <MainLayout>
        <template #filter>
            <div class="nav-card-filter">
                <div class="filter-row">
                    <ManagerDropdown
                        :items="managersStore.items"
                        v-model:selected-ids="selectedManagerIds"
                        prefix="Менеджер заявки:"
                        :show-sep="selectedManagerIds.length > 0 && selectedManagerIds.length < managersStore.items.length"
                        :show-count="selectedManagerIds.length > 0 && selectedManagerIds.length < managersStore.items.length"
                    />

                    <PeriodSwitcher v-model="period" v-model:date-range="dateRange" />
                </div>
                <SearchInput v-model="searchValue" placeholder="Поиск по архиву..." />
            </div>
        </template>

        <div class="section-block archive-page">
            <div class="section-title-row">
                <span class="section-title">Архив заявок</span>
                <span class="section-title-sep">·</span>
                <span class="section-title-count">{{ archiveStore.items.length }}</span>
            </div>

            <div v-if="archiveStore.loading" class="loading-state">Загрузка...</div>
            <StatsTable
                v-else-if="archiveStore.items.length"
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

            <div v-else class="archive-empty">
                <span v-if="searchValue || selectedManagerIds.length">Ничего не найдено</span>
                <span v-else>В архиве пока пусто</span>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useArchiveStore } from '@/stores/archive'
import { useManagersStore } from '@/stores/managers'
import MainLayout from '@/components/layout/MainLayout.vue'
import ManagerDropdown from '@/components/ui/ManagerDropdown.vue'
import PeriodSwitcher from '@/components/ui/PeriodSwitcher.vue'
import SearchInput from '@/components/ui/SearchInput.vue'
import Icon from '@/components/ui/Icon.vue'
import StatsTable from '@/components/ui/StatsTable.vue'
import { useSort } from '@/composables/useSort'

const archiveStore = useArchiveStore()
const managersStore = useManagersStore()

const searchValue = ref('')
const selectedManagerIds = ref([])
const period = ref('today')
const dateRange = ref(null)

const { sortKey, sortDirection, toggleSort } = useSort()

const columns = [
    { key: 'order_status_label', label: 'Статус', width: '180px' },
    { key: 'number', label: 'Номер заказа', width: '180px' },
    { key: 'processing_time', label: 'Время обработки', width: '180px', sortable: true },
    { key: 'shipped_at', label: 'Дата и время', width: '180px', sortable: true },
    { key: 'manager_name', label: 'Менеджер заявки', width: '220px' },
]

onMounted(() => {
    managersStore.fetchManagers()
    archiveStore.fetchArchive()
})

watch([searchValue, selectedManagerIds, period, dateRange], () => {
    archiveStore.fetchArchive({
        search: searchValue.value || undefined,
        manager_id: selectedManagerIds.value.length === 1 ? selectedManagerIds.value[0] : undefined,
        dateFrom: dateRange.value?.from || undefined,
        dateTo: dateRange.value?.to || undefined,
    })
}, { deep: true })

function formatDateTime(dateStr) {
    if (!dateStr) return '—'
    const d = new Date(dateStr)
    return d.toLocaleDateString('ru-RU', { day: 'numeric', month: 'long' }) +
        ' · ' + d.toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' })
}
</script>
