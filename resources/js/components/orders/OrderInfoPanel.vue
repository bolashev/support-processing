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
                    <div class="status-badge status-badge--open">Открыт</div>
                </div>

                <div class="modal-order-row">
                    <div class="modal-order-number">Заказ № КЛ5-0154204</div>
                    <div class="modal-state">
                        <span class="modal-state-dot modal-state-dot--green"></span>
                        <span class="modal-state-label">Новый</span>
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

                <EditableField
                    v-model="orderData.reserveDates"
                    label="Даты резерва:"
                    @save="onFieldSave('reserveDates', $event)"
                />

                <div class="info-row">
                    <span class="info-label">Водитель:</span>
                    <span class="info-value">{{ orderData.driver }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Телефон водителя:</span>
                    <span class="info-value info-value--link">{{ orderData.driverPhone }}</span>
                </div>
            </div>

            <div class="info-block">
                <div class="info-block-title">Информация о клиенте</div>

                <div class="info-row">
                    <span class="info-label">Наименование:</span>
                    <span class="info-value">{{ orderData.clientName }}</span>
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
                            <span class="modal-manager-name">Адволодкина Дарья Сергеевна</span>
                            <span class="modal-manager-role">Ведущий менеджер по продажам</span>
                        </div>
                    </div>
                </div>

                <div class="info-row">
                    <span class="info-label">Номер телефона:</span>
                    <span class="info-value info-value--link">+7 900 111-99-88</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Эл.почта</span>
                    <span class="info-value info-value--link">avdolodkina@trapeza.ru</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import EditableField from '@/components/ui/EditableField.vue'

const props = defineProps({
    orderData: { type: Object, required: true },
})

const onFieldSave = (field, value) => {
    console.log(`[OrderModal] Field "${field}" saved:`, value)
}
</script>
