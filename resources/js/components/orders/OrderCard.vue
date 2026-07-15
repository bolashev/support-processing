<template>
    <div class="order-card" @click="$emit('click', order.id)">
        <div class="order-card-header">
            <div class="order-card-status" :class="`order-card-status--${statusType}`">
                <span>{{ statusLabel }}</span>
            </div>
            <div class="order-card-state">
                <div class="order-card-dot" :class="`order-card-dot--${stateColor}`" />
                <span class="order-card-state-label">{{ stateLabel }}</span>
            </div>
        </div>

        <div class="order-card-body">
            <div class="order-card-top">
                <div class="order-card-number">Заказ № {{ order.number }}</div>

                <div class="order-card-counterparty">
                    <span>{{ order.counterparty }}</span>
                </div>

                <div class="order-card-info">
                    <div class="order-card-info-row">
                        <span class="order-card-info-label">{{ timerLabel }}</span>
                        <div class="order-card-info-value" :class="`order-card-info-value--${timerColor}`">
                            <Icon name="clock" :size="16" :color="timerStroke" />
                            <span>{{ timerValue }}</span>
                        </div>
                    </div>

                    <div class="order-card-divider" />

                    <div class="order-card-info-row">
                        <div class="order-card-info-left">
                            <Icon name="user" :size="16" color="#828282" />
                            <span class="order-card-manager-name">{{ managerName }}</span>
                            <div v-if="order.messages > 0" class="order-card-messages">
                                <Icon name="chat" :size="16" color="#727272" />
                                <span class="order-card-messages-count">{{ order.messages }}</span>
                            </div>
                        </div>
                        <div class="order-card-date">
                            <span>{{ order.date }}</span>
                            <span class="order-card-date-sep">·</span>
                            <span>{{ order.time }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import Icon from '@/components/ui/Icon.vue'

const props = defineProps({
    order: { type: Object, required: true },
})

defineEmits(['click'])

const statusLabel = computed(() => props.order.order_status_label || 'Открыт')
const statusType = computed(() => props.order.order_status_type || 'open')
const stateLabel = computed(() => props.order.request_status_label || 'Новый')
const stateColor = computed(() => props.order.request_status_color || 'green')
const timerLabel = computed(() => props.order.timer_label || 'Ожидание:')
const timerValue = computed(() => props.order.timer || '—')
const timerColor = computed(() => props.order.timer_color || 'green')

const timerStroke = computed(() => {
    const map = { green: '#3AB88D', orange: '#F7630C', red: '#E10101' }
    return map[props.order.timer_color] || '#3AB88D'
})

const managerName = computed(() => {
    if (props.order.is_self) return '(Вы)'
    return props.order.manager || 'Не назначен'
})
</script>
