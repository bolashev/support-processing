<template>
    <div class="stats-page-content">
        <StatsFilter
            v-model="period"
            :managers="managers"
            :selected-manager-ids="selectedManagerIds"
            manager-prefix="Менеджер:"
            @update:selected-manager-ids="selectedManagerIds = $event"
            @export="onExport"
        />

        <div class="stats-table-card">
            <table class="stats-table stats-table--support">
                <colgroup>
                    <col style="width: 180px" />
                    <col style="width: 154px" />
                    <col style="width: 135px" />
                    <col style="width: 157px" />
                    <col style="width: 157px" />
                    <col style="width: 171px" />
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
                        </td>
                        <td>{{ m.accepted }}</td>
                        <td>
                            <span :class="{ 'stats-value--alert': m.receiveTimeAlert }">{{ m.receiveTime }}</span>
                        </td>
                        <td>{{ m.returnWork }}</td>
                        <td>{{ m.shipmentWork }}</td>
                        <td>{{ m.lazarRegistrations }}</td>
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
    { key: 'accepted', label: 'Принято заявок', sorted: false },
    { key: 'receiveTime', label: 'Время на прием', sorted: false },
    { key: 'returnWork', label: 'Работа с возвратом', sorted: false },
    { key: 'shipmentWork', label: 'Работа с отгрузкой', sorted: false },
    { key: 'lazarRegistrations', label: 'Регистраций в Лазарь', sorted: false },
]

const managers = [
    { id: 1, name: 'Иванов Иван Иванович', accepted: 38, receiveTime: '15 мин.', receiveTimeAlert: false, returnWork: '1:30 ч.', shipmentWork: '4ч 20м', lazarRegistrations: 12 },
    { id: 2, name: 'Петрова Анна Сергеевна', accepted: 43, receiveTime: '31 мин.', receiveTimeAlert: true, returnWork: '1:05 ч.', shipmentWork: '3ч 30м', lazarRegistrations: 15 },
    { id: 3, name: 'Кузнецова Мария Александровна', accepted: 51, receiveTime: '15 мин.', receiveTimeAlert: false, returnWork: '1:15 ч.', shipmentWork: '5ч 10м', lazarRegistrations: 8 },
]

const filteredManagers = computed(() => {
    if (selectedManagerIds.value.length === 0) return managers
    return managers.filter(m => selectedManagerIds.value.includes(m.id))
})

function onExport() {
    // экспорт в .xlsx — будет реализован позже
}
</script>
