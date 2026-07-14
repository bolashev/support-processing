<template>
    <MainLayout>
        <template #filter>
            <div class="nav-card-filter">
                <div class="filter-row">
                    <ManagerDropdown
                        :items="managers"
                        v-model:selected-ids="selectedManagerIds"
                        prefix="Менеджер заявки:"
                        :show-sep="selectedManagerIds.length > 0 && selectedManagerIds.length < managers.length"
                        :show-count="selectedManagerIds.length > 0 && selectedManagerIds.length < managers.length"
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
                <span class="section-title-count">{{ filteredOrders.length }}</span>
            </div>

            <StatsTable
                v-if="filteredOrders.length"
                :columns="columns"
                :sort-key="sortKey"
                :sort-direction="sortDirection"
                @sort="toggleSort"
            >
                <tr v-for="order in filteredOrders" :key="order.id">
                    <td>
                        <span class="archive-status-badge">{{ order.status }}</span>
                    </td>
                    <td>Заказ № {{ order.number }}</td>
                    <td>{{ order.processingTime }}</td>
                    <td>{{ order.date }} · {{ order.time }}</td>
                    <td>
                        <div class="archive-manager">
                            <Icon name="user" :size="16" color="#282828" />
                            <span>{{ order.manager }}</span>
                            <span v-if="order.isSelf" class="archive-manager-self">(Вы)</span>
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
import { ref, computed } from 'vue'
import MainLayout from '@/components/layout/MainLayout.vue'
import ManagerDropdown from '@/components/ui/ManagerDropdown.vue'
import PeriodSwitcher from '@/components/ui/PeriodSwitcher.vue'
import SearchInput from '@/components/ui/SearchInput.vue'
import Icon from '@/components/ui/Icon.vue'
import StatsTable from '@/components/ui/StatsTable.vue'
import { useSort } from '@/composables/useSort'

const searchValue = ref('')
const selectedManagerIds = ref([])
const period = ref('today')
const dateRange = ref(null)

const { sortKey, sortDirection, toggleSort } = useSort()

const columns = [
    { key: 'status', label: 'Статус', width: '180px' },
    { key: 'number', label: 'Номер заказа', width: '180px' },
    { key: 'processingTime', label: 'Время обработки', width: '180px', sortable: true },
    { key: 'dateSort', label: 'Дата и время', width: '180px', sortable: true },
    { key: 'manager', label: 'Менеджер заявки', width: '220px' },
]

const managers = [
    { id: 1, name: 'Зайцева Екатерина Сергеевна' },
    { id: 2, name: 'Пронченко Зинаида Сергеевна' },
    { id: 3, name: 'Константинов Константин Конст...' },
    { id: 4, name: 'Дарья Сергеевна' },
]

const orders = [
    { id: 1, status: 'Закрыт. Отгружен 100%', number: 'КЛ5-0154204', processingTime: '01:29 ч.', processingMinutes: 89, date: '17 марта', time: '12:11', dateSort: '2026-03-17 12:11', manager: 'Дарья Сергеевна', isSelf: true },
    { id: 2, status: 'Закрыт. Отгружен 100%', number: 'КЛ5-0154205', processingTime: '03:00 ч.', processingMinutes: 180, date: '15 марта', time: '19:11', dateSort: '2026-03-15 19:11', manager: 'Илья Лукинов', isSelf: false },
    { id: 3, status: 'Закрыт. Отгружен 100%', number: 'КЛ5-0154206', processingTime: '03:00 ч.', processingMinutes: 180, date: '15 марта', time: '13:55', dateSort: '2026-03-15 13:55', manager: 'Константин Константино...', isSelf: false },
    { id: 4, status: 'Закрыт. Отгружен 100%', number: 'КЛ5-0154207', processingTime: '4 мин.', processingMinutes: 4, date: '15 марта', time: '13:55', dateSort: '2026-03-15 13:55', manager: 'Валерий Статов', isSelf: false },
]

const filteredOrders = computed(() => {
    let result = [...orders]

    if (selectedManagerIds.value.length > 0) {
        result = result.filter(o => {
            const manager = managers.find(m => m.name === o.manager)
            return manager && selectedManagerIds.value.includes(manager.id)
        })
    }

    if (searchValue.value) {
        const q = searchValue.value.toLowerCase()
        result = result.filter(o =>
            o.number.toLowerCase().includes(q) ||
            o.manager.toLowerCase().includes(q)
        )
    }

    if (sortKey.value) {
        result.sort((a, b) => {
            let valA, valB
            if (sortKey.value === 'processingTime') {
                valA = a.processingMinutes
                valB = b.processingMinutes
            } else if (sortKey.value === 'dateSort') {
                valA = a.dateSort
                valB = b.dateSort
            } else {
                valA = a[sortKey.value]
                valB = b[sortKey.value]
            }
            if (valA < valB) return sortDirection.value === 'asc' ? -1 : 1
            if (valA > valB) return sortDirection.value === 'asc' ? 1 : -1
            return 0
        })
    }

    return result
})
</script>
