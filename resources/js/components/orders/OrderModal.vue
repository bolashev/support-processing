<template>
    <Teleport to="body">
        <div v-if="visible" class="modal-overlay" @click.self="$emit('close')">
            <div v-if="store.currentOrder" class="modal-container">
                <div class="modal-panels">
                    <OrderInfoPanel :order-data="orderData" />
                    <OrderDocsPanel :documents="documents" />
                    <OrderHistoryPanel
                        :order-id="store.currentOrder.id"
                        :comments="store.currentOrder.comments || []"
                    />
                </div>

                <div class="modal-actions">
                    <button
                        v-if="store.currentOrder.request_status === 'new'"
                        class="modal-btn modal-btn--primary"
                        @click="takeOrder"
                    >
                        Взять в работу
                    </button>
                    <button class="modal-btn modal-btn--secondary">
                        <Icon name="onec" :size="20" color="#282828" />
                        <span>Открыть в 1С</span>
                    </button>
                    <button class="modal-btn modal-btn--secondary">
                        <Icon name="document" :size="20" color="#282828" />
                        <span>Сформировать счет</span>
                    </button>
                    <button class="modal-btn modal-btn--secondary">
                        <Icon name="invoice" :size="20" color="#282828" />
                        <span>Расходная накладная</span>
                    </button>
                    <button
                        v-if="store.currentOrder.request_status !== 'completed'"
                        class="modal-btn modal-btn--secondary"
                        @click="showReturnModal = true"
                    >
                        <Icon name="cancel" :size="20" color="#282828" />
                        <span>Вернуть заявку</span>
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
import { computed, watch } from 'vue'
import { useOrdersStore } from '@/stores/orders'
import { useManagersStore } from '@/stores/managers'
import Icon from '@/components/ui/Icon.vue'
import OrderInfoPanel from './OrderInfoPanel.vue'
import OrderDocsPanel from './OrderDocsPanel.vue'
import OrderHistoryPanel from './OrderHistoryPanel.vue'

const props = defineProps({
    visible: { type: Boolean, default: false },
    orderId: { type: Number, default: null },
})

const emit = defineEmits(['close'])
const store = useOrdersStore()
const managersStore = useManagersStore()

watch(() => props.visible, (val) => {
    document.body.style.overflow = val ? 'hidden' : ''
}, { immediate: true })

watch(() => props.orderId, (id) => {
    if (id && props.visible) {
        store.fetchOrder(id)
    }
})

const orderData = computed(() => {
    const o = store.currentOrder
    if (!o) return {}
    return {
        number: o.number,
        requestStatus: o.request_status_label,
        requestStatusColor: o.request_status_color,
        orderStatus: o.order_status_label,
        orderStatusType: o.order_status_type,
        channel: o.channel_label,
        counterpartyName: o.counterparty_name,
        counterpartyPartner: o.counterparty_partner,
        amount: o.amount,
        paymentMethod: o.payment_method,
        deliveryMethod: o.delivery_method,
        deliveryDetails: o.delivery_details,
        deliveryCost: o.delivery_cost,
        clientPhone: o.client_phone,
        clientEmail: o.client_email,
        clientInn: o.client_inn,
        clientCompany: o.client_company,
        salesManagerName: o.sales_manager_name,
        salesManagerEmail: o.sales_manager_email,
        salesManagerPhone: o.sales_manager_phone,
        processingAt: o.processing_at,
        assignedAt: o.assigned_at,
        reserveDate: o.reserve_date,
        manager: o.manager,
    }
})

const documents = computed(() => {
    return store.currentOrder?.documents || []
})

async function takeOrder() {
    try {
        await store.takeOrder(props.orderId)
        managersStore.fetchManagers()
    } catch (e) {
        alert(e.response?.data?.message || 'Ошибка')
    }
}
</script>

<style scoped>
.modal-container--loading {
    opacity: 0.5;
    pointer-events: none;
    transition: opacity 0.2s ease;
}
</style>
