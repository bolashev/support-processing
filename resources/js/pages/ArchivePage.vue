<template>
    <div class="page-root">
        <PortalHeader />

        <div class="content-area">
            <div class="nav-card-wrap">
                <NavTabs />

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
            </div>

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
                                        <svg class="stats-sort-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <rect x="14" y="5" width="6" height="2" transform="rotate(180 14 5)" fill="#959595"/>
                                            <rect x="14" y="9" width="9" height="2" transform="rotate(180 14 9)" fill="#959595"/>
                                            <rect x="14" y="13" width="12" height="2" transform="rotate(180 14 13)" fill="#959595"/>
                                        </svg>
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
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M7.99984 8.66667C9.84079 8.66667 11.3332 7.17428 11.3332 5.33333C11.3332 3.49238 9.84079 2 7.99984 2C6.15889 2 4.6665 3.49238 4.6665 5.33333C4.6665 7.17428 6.15889 8.66667 7.99984 8.66667ZM7.99984 8.66667C9.41432 8.66667 10.7709 9.22857 11.7711 10.2288C12.7713 11.229 13.3332 12.5855 13.3332 14M7.99984 8.66667C6.58535 8.66667 5.22879 9.22857 4.2286 10.2288C3.22841 11.229 2.6665 12.5855 2.6665 14" stroke="#282828" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
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
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import PortalHeader from '../components/layout/PortalHeader.vue'
import NavTabs from '../components/layout/NavTabs.vue'
import ManagerDropdown from '../components/ui/ManagerDropdown.vue'
import PeriodSwitcher from '../components/ui/PeriodSwitcher.vue'
import SearchInput from '../components/ui/SearchInput.vue'

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

const filteredOrders = ref(orders)
</script>
