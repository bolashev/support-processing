<template>
    <div class="stats-page-content">
        <StatsFilter
            v-model="period"
            :managers="managers"
            :selected-manager-ids="selectedManagerIds"
            @update:selected-manager-ids="selectedManagerIds = $event"
            @export="onExport"
        />

        <div class="stats-table-card">
            <table class="stats-table">
                <colgroup>
                    <col />
                    <col style="width: 154px" />
                    <col style="width: 135px" />
                    <col style="width: 144px" />
                    <col style="width: 130px" />
                    <col style="width: 92px" />
                </colgroup>
                <thead>
                    <tr>
                        <th v-for="col in columns" :key="col.key">
                            <div class="stats-col-header">
                                <span>{{ col.label }}</span>
                                <svg class="stats-sort-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <rect x="14" y="5" width="6" height="2" transform="rotate(180 14 5)" :fill="col.sorted ? '#878B99' : '#959595'"/>
                                    <rect x="14" y="9" width="9" height="2" transform="rotate(180 14 9)" :fill="col.sorted ? '#878B99' : '#959595'"/>
                                    <rect x="14" y="13" width="12" height="2" transform="rotate(180 14 13)" :fill="col.sorted ? '#878B99' : '#959595'"/>
                                </svg>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
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
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import StatsFilter from '../components/statistics/StatsFilter.vue'

const period = ref('today')
const selectedManagerIds = ref([])

const columns = [
    { key: 'name', label: 'ФИО менеджера', sorted: false },
    { key: 'period', label: 'Период активности', sorted: true },
    { key: 'total', label: 'Всего отгружено', sorted: false },
    { key: 'processing', label: 'Через процессинг', sorted: false },
    { key: 'percent', label: '% Процессинга', sorted: false },
    { key: 'avg', label: 'Ср. в день', sorted: false },
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

const filteredManagers = computed(() => {
    if (selectedManagerIds.value.length === 0) return managers
    return managers.filter(m => selectedManagerIds.value.includes(m.id))
})

function percent(m) {
    if (!m.total) return '0%'
    return Math.round((m.processing / m.total) * 100) + '%'
}

function onExport() {
    // экспорт в .xlsx — будет реализован позже
}
</script>
