<template>
    <div class="page-root">
        <PortalHeader />

        <div class="content-area">
            <div class="nav-card-wrap">
                <NavTabs />

                <IncomingFilter />
            </div>

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
                            :cards="shippedOrders"
                            :sortable="true"
                            empty-text="Вы еще ничего не отгружали.<br>Все завершенные заказы будут<br>храниться здесь"
                            @card-click="openOrder"
                        />
                    </div>
                </div>
            </div>
        </div>

        <OrderModal :visible="!!selectedOrderId" @close="selectedOrderId = null" />
    </div>
</template>

<script setup>
import { ref } from 'vue'
import PortalHeader from '../components/layout/PortalHeader.vue'
import NavTabs from '../components/layout/NavTabs.vue'
import IncomingFilter from '../components/orders/IncomingFilter.vue'
import KanbanColumn from '../components/orders/KanbanColumn.vue'
import OrderModal from '../components/orders/OrderModal.vue'

const selectedOrderId = ref(null)

function openOrder(id) {
    selectedOrderId.value = id
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
