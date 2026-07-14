<template>
    <Teleport to="body">
        <div v-if="visible" class="modal-overlay" @click.self="$emit('close')">
            <div class="modal-container">
                <div class="modal-panels">
                    <OrderInfoPanel :order-data="orderData" />
                    <OrderDocsPanel :documents="documents" />
                    <OrderHistoryPanel :comments="comments" />
                </div>

                <div class="modal-actions">
                    <button class="modal-btn modal-btn--primary">
                        Взять в работу
                    </button>
                    <button class="modal-btn modal-btn--secondary">
                        <Icon name="document" :size="20" color="#282828" />
                        Открыть в 1С
                    </button>
                    <button class="modal-btn modal-btn--secondary">
                        <Icon name="document" :size="20" color="#282828" />
                        Сформировать счет
                    </button>
                    <button class="modal-btn modal-btn--secondary">
                        <Icon name="document" :size="20" color="black" />
                        Расходная накладная
                    </button>
                    <button class="modal-btn modal-btn--secondary">
                        <Icon name="cancel" :size="20" color="#282828" />
                        Вернуть заявку
                    </button>
                </div>
            </div>

            <div class="modal-close-wrap">
                <button class="modal-close" @click="$emit('close')">
                    <Icon name="close" :size="16" color="#222222" />
                </button>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { reactive, watch } from 'vue'
import Icon from '@/components/ui/Icon.vue'
import OrderInfoPanel from './OrderInfoPanel.vue'
import OrderDocsPanel from './OrderDocsPanel.vue'
import OrderHistoryPanel from './OrderHistoryPanel.vue'

const props = defineProps({
    visible: { type: Boolean, default: false },
})

defineEmits(['close'])

const orderData = reactive({
    deliveryMethod: 'Самовывоз',
    reserveDates: {
        start: new Date(2024, 4, 24),
        end: new Date(2024, 4, 27),
    },
    driver: 'Петров Иван',
    driverPhone: '+7 900 111-22-33',
    clientName: 'ООО «ТехноПром Инжиниринг»',
    clientInn: '7710140679',
    clientPhone: '+7 900 111-22-33',
    clientEmail: 'avdolodkina@trapeza.ru',
})

const documents = [
    { id: 1, name: 'Расходная-накладная 55499', size: '1.7 МБ' },
    { id: 2, name: 'Счёт № СЧ4-0010422', size: '0.7 МБ' },
]

const comments = [
    {
        id: 1,
        author: 'Ольга Ситникова',
        avatar: 'https://placehold.co/30x30',
        text: 'Товар собран, ждем машину',
        time: '14 марта 2026 · 10:30',
    },
    {
        id: 2,
        author: 'Сергей Кудряш',
        avatar: 'https://placehold.co/30x30',
        text: 'Клиент подтвердил самовывоз на понедельник',
        time: '12 марта 2026 · 15:50',
    },
]

watch(() => props.visible, (val) => {
    document.body.style.overflow = val ? 'hidden' : ''
})
</script>
