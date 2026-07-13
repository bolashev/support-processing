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

                    <PeriodSwitcher v-model="period" />
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

            <div v-if="filteredOrders.length" class="stats-table-card">
                <table class="stats-table">
                    <colgroup>
                        <col style="width: 180px" />
                        <col style="width: 180px" />
                        <col style="width: 180px" />
                        <col style="width: 180px" />
                        <col style="width: 220px" />
                    </colgroup>
                    <thead>
                        <tr>
                            <th>
                                <div class="stats-col-header"><span>Статус</span></div>
                            </th>
                            <th>
                                <div class="stats-col-header"><span>Номер заказа</span></div>
                            </th>
                            <th>
                                <div class="stats-col-header"><span>Время обработки</span></div>
                            </th>
                            <th>
                                <div class="stats-col-header">
                                    <span>Дата и время</span>
                                    <Icon name="sort" :size="16" class="stats-sort-icon" color="#959595" />
                                </div>
                            </th>
                            <th>
                                <div class="stats-col-header"><span>Менеджер заявки</span></div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
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
                    </tbody>
                </table>
            </div>

            <div v-else class="archive-empty">
                <span v-if="searchValue || selectedManagerIds.length">Ничего не найдено</span>
                <span v-else>В архиве пока пусто</span>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import MainLayout from '../components/layout/MainLayout.vue'
import ManagerDropdown from '../components/ui/ManagerDropdown.vue'
import PeriodSwitcher from '../components/ui/PeriodSwitcher.vue'
import SearchInput from '../components/ui/SearchInput.vue'
import Icon from '../components/ui/Icon.vue'

const searchValue = ref('')
const selectedManagerIds = ref([])
const period = ref('today')

const managers = [
    { id: 1, name: 'Зайцева Екатерина Сергеевна' },
    { id: 2, name: 'Пронченко Зинаида Сергеевна' },
    { id: 3, name: 'Константинов Константин Конст...' },
    { id: 4, name: 'Дарья Сергеевна' },
]

const orders = [
    { id: 1, status: 'Закрыт. Отгружен 100%', number: 'КЛ5-0154204', processingTime: '01:29 ч.', date: '17 марта', time: '12:11', manager: '(Вы)', isSelf: true },
    { id: 2, status: 'Закрыт. Отгружен 100%', number: 'КЛ5-0154205', processingTime: '03:00 ч.', date: '15 марта', time: '19:11', manager: 'Илья Лукинов', isSelf: false },
    { id: 3, status: 'Закрыт. Отгружен 100%', number: 'КЛ5-0154206', processingTime: '03:00 ч.', date: '15 марта', time: '13:55', manager: 'Константин Константино...', isSelf: false },
    { id: 4, status: 'Закрыт. Отгружен 100%', number: 'КЛ5-0154207', processingTime: '4 мин.', date: '15 марта', time: '13:55', manager: 'Валерий Статов', isSelf: false },
]

const filteredOrders = computed(() => {
    let result = orders

    if (selectedManagerIds.value.length > 0) {
        result = result.filter(o => {
            const manager = managers.find(m => m.name === o.manager || (o.isSelf && m.name === 'Вы'))
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

    return result
})
</script>
