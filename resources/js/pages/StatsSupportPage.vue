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

        <StatsTable :columns="columns" variant="support">
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
        </StatsTable>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import StatsFilter from '../components/statistics/StatsFilter.vue'
import StatsTable from '../components/ui/StatsTable.vue'

const period = ref('today')
const selectedManagerIds = ref([])

const columns = [
    { key: 'name', label: 'ФИО менеджера', width: '180px' },
    { key: 'accepted', label: 'Принято заявок', width: '154px' },
    { key: 'receiveTime', label: 'Время на прием', width: '135px' },
    { key: 'returnWork', label: 'Работа с возвратом', width: '157px' },
    { key: 'shipmentWork', label: 'Работа с отгрузкой', width: '157px' },
    { key: 'lazarRegistrations', label: 'Регистраций в Лазарь', width: '171px' },
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
