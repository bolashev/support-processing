<template>
    <div class="stats-page-content">
        <StatsFilter
            v-model="period"
            v-model:date-range="dateRange"
            :managers="managers"
            :selected-manager-ids="selectedManagerIds"
            manager-prefix="Менеджер:"
            @update:selected-manager-ids="selectedManagerIds = $event"
            @export="onExport"
        />

        <StatsTable
            :columns="columns"
            variant="support"
            :sort-key="sortKey"
            :sort-direction="sortDirection"
            @sort="toggleSort"
        >
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
import StatsFilter from '@/components/statistics/StatsFilter.vue'
import StatsTable from '@/components/ui/StatsTable.vue'
import { useSort } from '@/composables/useSort'

const period = ref('today')
const dateRange = ref(null)
const selectedManagerIds = ref([])

const { sortKey, sortDirection, toggleSort } = useSort()

const columns = [
    { key: 'name', label: 'ФИО менеджера', width: '180px', sortable: true },
    { key: 'accepted', label: 'Принято заказов', width: '154px', sortable: true },
    { key: 'receiveTime', label: 'Время на прием', width: '135px', sortable: true },
    { key: 'returnWork', label: 'Работа с возвратом', width: '157px', sortable: true },
    { key: 'shipmentWork', label: 'Работа с отгрузкой', width: '157px', sortable: true },
    { key: 'lazarRegistrations', label: 'Регистраций в Лазарь', width: '171px', sortable: true },
]

const managers = [
    { id: 1, name: 'Иванов Иван Иванович', accepted: 38, receiveTime: '15 мин.', receiveTimeAlert: false, receiveTimeMinutes: 15, returnWork: '1:30 ч.', returnWorkMinutes: 90, shipmentWork: '4ч 20м', shipmentWorkMinutes: 260, lazarRegistrations: 12 },
    { id: 2, name: 'Петрова Анна Сергеевна', accepted: 43, receiveTime: '31 мин.', receiveTimeAlert: true, receiveTimeMinutes: 31, returnWork: '1:05 ч.', returnWorkMinutes: 65, shipmentWork: '3ч 30м', shipmentWorkMinutes: 210, lazarRegistrations: 15 },
    { id: 3, name: 'Кузнецова Мария Александровна', accepted: 51, receiveTime: '15 мин.', receiveTimeAlert: false, receiveTimeMinutes: 15, returnWork: '1:15 ч.', returnWorkMinutes: 75, shipmentWork: '5ч 10м', shipmentWorkMinutes: 310, lazarRegistrations: 8 },
]

const filteredManagers = computed(() => {
    let result = selectedManagerIds.value.length === 0
        ? [...managers]
        : managers.filter(m => selectedManagerIds.value.includes(m.id))

    if (sortKey.value) {
        result.sort((a, b) => {
            let valA, valB
            if (sortKey.value === 'receiveTime') {
                valA = a.receiveTimeMinutes
                valB = b.receiveTimeMinutes
            } else if (sortKey.value === 'returnWork') {
                valA = a.returnWorkMinutes
                valB = b.returnWorkMinutes
            } else if (sortKey.value === 'shipmentWork') {
                valA = a.shipmentWorkMinutes
                valB = b.shipmentWorkMinutes
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

function onExport() {
    // экспорт в .xlsx — будет реализован позже
}
</script>
