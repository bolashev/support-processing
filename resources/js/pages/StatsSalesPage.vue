<template>
    <div class="stats-page-content">
        <StatsFilter
            v-model="period"
            v-model:date-range="dateRange"
            :managers="managers"
            :selected-manager-ids="selectedManagerIds"
            @update:selected-manager-ids="selectedManagerIds = $event"
            @export="onExport"
        />

        <StatsTable
            :columns="columns"
            :sort-key="sortKey"
            :sort-direction="sortDirection"
            @sort="toggleSort"
        >
            <tr v-for="m in filteredManagers" :key="m.id">
                <td>
                    <span class="stats-manager-name">{{ m.name }}</span>
                    <span v-if="m.isSelf" class="stats-manager-self"> (Вы)</span>
                </td>
                <td>{{ m.period }}</td>
                <td>{{ m.total }}</td>
                <td>{{ m.processing }}</td>
                <td>{{ percent(m) }}</td>
                <td>{{ m.avg }}</td>
            </tr>
        </StatsTable>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import StatsFilter from '../components/statistics/StatsFilter.vue'
import StatsTable from '../components/ui/StatsTable.vue'

const period = ref('today')
const dateRange = ref(null)
const selectedManagerIds = ref([])
const sortKey = ref(null)
const sortDirection = ref('none')

const columns = [
    { key: 'name', label: 'ФИО менеджера', sortable: true },
    { key: 'period', label: 'Период активности', sortable: true },
    { key: 'total', label: 'Всего отгружено', sortable: true },
    { key: 'processing', label: 'Через процессинг', sortable: true },
    { key: 'percent', label: '% Процессинга', sortable: true },
    { key: 'avg', label: 'Ср. в день', sortable: true },
]

const managers = [
    { id: 1, name: 'Адволодкина Дарья Сергеевна', isSelf: true, period: '19.10.25 — н.в.', total: 450, processing: 225, avg: 18 },
    { id: 2, name: 'Иванов Иван Иванович', period: '19.10.25 — н.в.', total: 450, processing: 225, avg: 18 },
    { id: 3, name: 'Кузнецова Мария Александровна', period: '01.11.25 — 01.03.26', total: 300, processing: 120, avg: 22 },
    { id: 4, name: 'Петрова Анна Сергеевна', period: '01.11.25 — 01.03.26', total: 520, processing: 380, avg: 31 },
    { id: 5, name: 'Смирнов Алексей Петрович', period: '01.11.25 — 01.03.26', total: 520, processing: 380, avg: 31 },
    { id: 6, name: 'Соколов Дмитрий Игоревич', period: '01.11.25 — 01.03.26', total: 480, processing: 300, avg: 27 },
    { id: 7, name: 'Морозова Елена Владимировна', period: '15.09.25 — н.в.', total: 610, processing: 410, avg: 34 },
    { id: 8, name: 'Волков Сергей Андреевич', period: '15.09.25 — н.в.', total: 390, processing: 210, avg: 20 },
    { id: 9, name: 'Зайцева Екатерина Сергеевна', period: '20.08.25 — 28.02.26', total: 720, processing: 500, avg: 38 },
    { id: 10, name: 'Орлов Павел Михайлович', period: '20.08.25 — н.в.', total: 410, processing: 260, avg: 23 },
    { id: 11, name: 'Лебедева Ольга Николаевна', period: '01.12.25 — н.в.', total: 280, processing: 190, avg: 25 },
    { id: 12, name: 'Григорьев Максим Александрович', period: '01.12.25 — н.в.', total: 350, processing: 240, avg: 29 },
]

function toggleSort(key) {
    if (sortKey.value === key) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
    } else {
        sortKey.value = key
        sortDirection.value = 'desc'
    }
}

const filteredManagers = computed(() => {
    let result = selectedManagerIds.value.length === 0
        ? [...managers]
        : managers.filter(m => selectedManagerIds.value.includes(m.id))

    if (sortKey.value) {
        result.sort((a, b) => {
            let valA, valB
            if (sortKey.value === 'percent') {
                valA = a.total ? (a.processing / a.total) : 0
                valB = b.total ? (b.processing / b.total) : 0
            } else {
                valA = a[sortKey.value]
                valB = b[sortKey.value]
            }
            if (typeof valA === 'string') {
                return sortDirection.value === 'asc'
                    ? valA.localeCompare(valB)
                    : valB.localeCompare(valA)
            }
            return sortDirection.value === 'asc' ? valA - valB : valB - valA
        })
    }

    return result
})

function percent(m) {
    if (!m.total) return '0%'
    return Math.round((m.processing / m.total) * 100) + '%'
}

function onExport() {
    // экспорт в .xlsx — будет реализован позже
}
</script>
