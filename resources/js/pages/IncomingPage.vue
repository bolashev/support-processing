<template>
    <MainLayout>
        <template #filter>
            <IncomingFilter />
        </template>

        <div class="section-block">
            <div class="section-title-row">
                <span class="section-title">Входящие заявки</span>
            </div>
            <div class="columns-wrap">
                <div class="columns-row">
                    <KanbanColumn
                        title="Новые"
                        :cards="newOrders"
                        empty-text="Пока здесь пусто.<br>Как только появятся новые заявки,<br>они отобразятся в этом списке"
                        @card-click="openOrder"
                    />
                    <KanbanColumn
                        title="В работе"
                        :cards="inProgressOrders"
                        empty-text="Нет заявок в работе"
                        @card-click="openOrder"
                    />
                    <KanbanColumn
                        title="Отгруженные"
                        :cards="sortedShippedOrders"
                        :sortable="true"
                        :sort-direction="shippedSortDirection"
                        :sort-label="shippedSortLabel"
                        empty-text="Вы еще ничего не отгружали.<br>Все завершенные заказы будут<br>храниться здесь"
                        @card-click="openOrder"
                        @sort="toggleShippedSort"
                    />
                </div>
            </div>
        </div>

        <OrderModal :visible="!!selectedOrderId" @close="selectedOrderId = null" />
    </MainLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import MainLayout from '../components/layout/MainLayout.vue'
import IncomingFilter from '../components/orders/IncomingFilter.vue'
import KanbanColumn from '../components/orders/KanbanColumn.vue'
import OrderModal from '../components/orders/OrderModal.vue'

const selectedOrderId = ref(null)
const shippedSortDirection = ref('none')

function openOrder(id) {
    selectedOrderId.value = id
}

function toggleShippedSort() {
    if (shippedSortDirection.value === 'none') {
        shippedSortDirection.value = 'desc'
    } else if (shippedSortDirection.value === 'desc') {
        shippedSortDirection.value = 'asc'
    } else {
        shippedSortDirection.value = 'desc'
    }
}

const shippedSortLabel = 'По дате'

const sortedShippedOrders = computed(() => {
    if (shippedSortDirection.value === 'none') return shippedOrders
    const dir = shippedSortDirection.value === 'asc' ? 1 : -1
    return [...shippedOrders].sort((a, b) => {
        const tA = parseTime(a.timer)
        const tB = parseTime(b.timer)
        return (tA - tB) * dir
    })
})

function parseTime(str) {
    const h = str.match(/(\d+)\s*ч/)
    const m = str.match(/(\d+)\s*мин/)
    return (h ? parseInt(h[1]) * 60 : 0) + (m ? parseInt(m[1]) : 0)
}

const newOrders = [
    {
        id: 1,
        number: 'КЛ4-0154299',
        counterparty: 'ООО «Сырники по утрам»',
        statusLabel: 'Открыт',
        statusType: 'open',
        stateLabel: 'Новый',
        stateColor: 'green',
        timerLabel: 'Ожидание:',
        timer: '4 мин.',
        timerColor: 'green',
        manager: null,
        isSelf: false,
        messages: 0,
        date: '04 марта',
        time: '10:30',
    },
]

const inProgressOrders = [
    {
        id: 2,
        number: 'КЛ5-0154204',
        counterparty: 'ООО «Карамельный экстаз»',
        statusLabel: 'Открыт. Оплачен 10%',
        statusType: 'paid',
        stateLabel: 'В работе',
        stateColor: 'orange',
        timerLabel: 'В работе:',
        timer: '02:31 ч.',
        timerColor: 'orange',
        manager: null,
        isSelf: true,
        messages: 1,
        date: '16 марта',
        time: '09:17',
    },
    {
        id: 3,
        number: 'КЛ5-0154204',
        counterparty: 'ООО «Петров и Ко»',
        statusLabel: 'Открыт. Отгружен 10%',
        statusType: 'shipped',
        stateLabel: 'В работе',
        stateColor: 'orange',
        timerLabel: 'В работе:',
        timer: '3 часа',
        timerColor: 'red',
        manager: 'Илья Лукинов',
        isSelf: false,
        messages: 0,
        date: '04 марта',
        time: '10:30',
    },
    {
        id: 4,
        number: 'КЛ5-0154204',
        counterparty: 'ООО «Петров и Ко»',
        statusLabel: 'Ожидает оплаты',
        statusType: 'wait',
        stateLabel: 'В работе',
        stateColor: 'orange',
        timerLabel: 'В работе:',
        timer: '4 часа',
        timerColor: 'red',
        manager: 'Валерий Статов',
        isSelf: false,
        messages: 0,
        date: '03 марта',
        time: '10:30',
    },
]

const shippedOrders = [
    {
        id: 5,
        number: 'КЛ5-0154204',
        counterparty: 'ООО «Финиш»',
        statusLabel: 'Закрыт. Отгружен 100%',
        statusType: 'closed',
        stateLabel: 'Завершен',
        stateColor: 'purple',
        timerLabel: 'Время обработки:',
        timer: '01:29 ч.',
        timerColor: 'default',
        manager: null,
        isSelf: true,
        messages: 3,
        date: '17 марта',
        time: '12:11',
    },
    {
        id: 6,
        number: 'КЛ5-0154204',
        counterparty: 'ООО «Финиш»',
        statusLabel: 'Закрыт. Отгружен 100%',
        statusType: 'closed',
        stateLabel: 'Завершен',
        stateColor: 'purple',
        timerLabel: 'Время обработки:',
        timer: '40 мин.',
        timerColor: 'default',
        manager: 'Константин Конста...',
        isSelf: false,
        messages: 0,
        date: '17 марта',
        time: '12:11',
    },
]
</script>
