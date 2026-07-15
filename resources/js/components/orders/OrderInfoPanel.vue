<template>
    <div class="modal-panel modal-panel--info">
        <div class="modal-panel-header">
            <div class="modal-panel-header-inner">
                <span class="modal-panel-title">Информация о заказе</span>
            </div>
        </div>

        <div class="modal-section-fixed">
            <div class="modal-section">
                <div class="modal-status">
                    <div class="status-badge status-badge--open">{{ orderData.orderStatus || 'Открыт' }}</div>
                </div>

                <div class="modal-order-row">
                    <div class="modal-order-number">Заказ № {{ orderData.number }}</div>
                    <div class="modal-state">
                        <span class="modal-state-dot modal-state-dot--green"></span>
                        <span class="modal-state-label">{{ orderData.requestStatus || 'Новый' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-panel-body">
            <div class="info-block">
                <div class="info-block-title">Информация по отгрузке</div>

                <div class="info-row">
                    <span class="info-label">Склад отгрузки:</span>
                    <span class="info-value">ОП «Никольское»</span>
                </div>

                <EditableField
                    v-model="orderData.deliveryMethod"
                    label="Способ доставки:"
                    @save="onFieldSave('deliveryMethod', $event)"
                />

                <div
                    ref="dateFieldRef"
                    class="editable-field"
                    @click="openPicker(dateFieldRef)"
                >
                    <div class="editable-field__content">
                        <span class="editable-field__label">Даты резерва:</span>
                        <span class="editable-field__value">{{ displayReserveDates }}</span>
                    </div>
                    <span class="editable-field__icon">
                        <Icon name="edit" :size="16" color="#959595" />
                    </span>
                </div>

                <DatePickerPopup
                    :visible="showPicker"
                    :left-year="leftYear"
                    :left-month="leftMonth"
                    :right-year="rightYear"
                    :right-month="rightMonth"
                    :selected-start="selectedStart"
                    :selected-end="selectedEnd"
                    :hover-date="hoverDate"
                    :popup-position="popupPosition"
                    @close="close"
                    @select="onSelectDate"
                    @hover="onHoverDate"
                    @prev="goPrev"
                    @next="goNext"
                    @apply="applyDates"
                />

                <div class="info-row">
                    <span class="info-label">Водитель:</span>
                    <span class="info-value">{{ orderData.driver || '—' }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Телефон водителя:</span>
                    <span class="info-value info-value--link">{{ orderData.driverPhone || '—' }}</span>
                </div>
            </div>

            <div class="info-block">
                <div class="info-block-title">Информация о клиенте</div>

                <div class="info-row">
                    <span class="info-label">Наименование:</span>
                    <span class="info-value">{{ orderData.counterpartyName }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">ИНН:</span>
                    <span class="info-value">{{ orderData.clientInn }}</span>
                </div>

                <EditableField
                    v-model="orderData.clientPhone"
                    label="Телефон:"
                    link
                    @save="onFieldSave('clientPhone', $event)"
                />

                <EditableField
                    v-model="orderData.clientEmail"
                    label="Эл.почта:"
                    link
                    @save="onFieldSave('clientEmail', $event)"
                />
            </div>

            <div class="info-block">
                <div class="info-block-title">Ответственный менеджер</div>

                <div class="info-row">
                    <div class="modal-manager">
                        <img class="modal-manager-avatar" src="https://placehold.co/43x43" alt="" />
                        <div class="modal-manager-info">
                            <span class="modal-manager-name">{{ orderData.salesManagerName || 'Не назначен' }}</span>
                            <span class="modal-manager-role">{{ orderData.salesManagerPhone }}</span>
                        </div>
                    </div>
                </div>

                <div class="info-row">
                    <span class="info-label">Номер телефона:</span>
                    <span class="info-value info-value--link">{{ orderData.salesManagerPhone }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Эл.почта</span>
                    <span class="info-value info-value--link">{{ orderData.salesManagerEmail }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { format } from 'date-fns'
import { ru } from 'date-fns/locale'
import EditableField from '@/components/ui/EditableField.vue'
import Icon from '@/components/ui/Icon.vue'
import DatePickerPopup from '@/components/ui/DatePicker/DatePickerPopup.vue'
import { useDatePicker } from '@/components/ui/DatePicker/useDatePicker'

const props = defineProps({
    orderData: { type: Object, required: true },
})

const dateFieldRef = ref(null)

const {
    showPicker, selectedStart, selectedEnd, hoverDate, popupPosition,
    leftMonth, leftYear, rightMonth, rightYear,
    open, close, goPrev, goNext, onSelectDate, onHoverDate, getResult,
} = useDatePicker()

const displayReserveDates = computed(() => {
    const d = props.orderData.reserveDates
    if (d?.start && d?.end) {
        const start = format(d.start, 'dd.MM.yyyy', { locale: ru })
        const end = format(d.end, 'dd.MM.yyyy', { locale: ru })
        return `${start} — ${end}`
    }
    if (d?.start) {
        return format(d.start, 'dd.MM.yyyy', { locale: ru })
    }
    return 'Не указано'
})

function openPicker(el) {
    open(el, props.orderData.reserveDates)
}

function applyDates() {
    const result = getResult()
    props.orderData.reserveDates = result
    onFieldSave('reserveDates', result)
    close()
}

const onFieldSave = (field, value) => {
    console.log(`[OrderModal] Field "${field}" saved:`, value)
}
</script>
