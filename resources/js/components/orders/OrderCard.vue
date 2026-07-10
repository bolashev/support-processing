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
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M14.5 8.25C14.5 11.7019 11.7019 14.5 8.25 14.5C4.79813 14.5 2 11.7019 2 8.25C2 4.79813 4.79813 2 8.25 2M14.375 7C14.2707 6.48834 14.1028 5.99176 13.875 5.52188M13.25 4.49938C12.8947 4.02559 12.4738 3.60471 12 3.24938M10.9775 2.625C10.5078 2.39729 10.0114 2.22932 9.5 2.125" :stroke="timerStroke" stroke-width="1.5"/>
                                <path d="M8.25 5.125V8.25L10.75 10.75" :stroke="timerStroke" stroke-width="1.5"/>
                            </svg>
                            <span>{{ timerValue }}</span>
                        </div>
                    </div>

                    <div class="order-card-divider" />

                    <div class="order-card-info-row">
                        <div class="order-card-info-left">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M7.99984 8.66667C9.84079 8.66667 11.3332 7.17428 11.3332 5.33333C11.3332 3.49238 9.84079 2 7.99984 2C6.15889 2 4.6665 3.49238 4.6665 5.33333C4.6665 7.17428 6.15889 8.66667 7.99984 8.66667ZM7.99984 8.66667C9.41432 8.66667 10.7709 9.22857 11.7711 10.2288C12.7713 11.229 13.3332 12.5855 13.3332 14M7.99984 8.66667C6.58535 8.66667 5.22879 9.22857 4.2286 10.2288C3.22841 11.229 2.6665 12.5855 2.6665 14" stroke="#828282" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="order-card-manager-name">{{ managerName }}</span>
                            <div v-if="order.messages > 0" class="order-card-messages">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M6.66683 2H9.3335C12.279 2 14.6668 4.38781 14.6668 7.33333C14.6668 10.2789 12.279 12.6667 9.3335 12.6667V15C6.00016 13.6667 1.3335 11.6667 1.3335 7.33333C1.3335 4.38781 3.72131 2 6.66683 2ZM8.00016 11.3333H9.3335C11.5426 11.3333 13.3335 9.54247 13.3335 7.33333C13.3335 5.12419 11.5426 3.33333 9.3335 3.33333H6.66683C4.45769 3.33333 2.66683 5.12419 2.66683 7.33333C2.66683 9.74 4.30822 11.3104 8.00016 12.9865V11.3333Z" fill="#727272"/>
                                </svg>
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

const props = defineProps({
    order: { type: Object, required: true },
})

defineEmits(['click'])

const statusLabel = computed(() => props.order.statusLabel || 'Открыт')
const statusType = computed(() => props.order.statusType || 'open')
const stateLabel = computed(() => props.order.stateLabel || 'Новый')
const stateColor = computed(() => props.order.stateColor || 'green')
const timerLabel = computed(() => props.order.timerLabel || 'Ожидание:')
const timerValue = computed(() => props.order.timer || '—')
const timerColor = computed(() => props.order.timerColor || 'green')

const timerStroke = computed(() => {
    const map = { green: '#3AB88D', orange: '#F7630C', red: '#E10101' }
    return map[props.order.timerColor] || '#3AB88D'
})

const managerName = computed(() => {
    if (props.order.isSelf) return '(Вы)'
    return props.order.manager || 'Не назначен'
})
</script>
