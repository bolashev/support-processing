<template>
    <Teleport to="body">
        <div v-if="visible" class="modal-overlay" @click.self="$emit('close')">
            <div class="modal-container">
                <div class="modal-panels">
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

                    <div class="modal-panel modal-panel--docs">
                        <div class="modal-panel-header">
                            <div class="modal-panel-header-inner">
                                <span class="modal-panel-title">Документы</span>
                            </div>
                        </div>

                        <div class="modal-panel-body">
                            <div class="modal-docs-list">
                                <div class="modal-doc-item">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M3.33333 18.3334H15C15.442 18.3334 15.866 18.1578 16.1785 17.8452C16.4911 17.5326 16.6667 17.1087 16.6667 16.6667V5.83335L12.5 1.66669H5C4.55797 1.66669 4.13405 1.84228 3.82149 2.15484C3.50893 2.4674 3.33333 2.89133 3.33333 3.33335V6.66669M11.6667 1.66669V5.00002C11.6667 5.44205 11.8423 5.86597 12.1548 6.17853C12.4674 6.49109 12.8913 6.66669 13.3333 6.66669H16.6667M2.5 12.5L4.16667 14.1667L7.5 10.8334" stroke="#4D79FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="modal-doc-info">
                                        <span class="modal-doc-name">Расходная-накладная 55499</span>
                                        <span class="modal-doc-size">1.7 МБ</span>
                                    </div>
                                </div>
                                <div class="modal-doc-item">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M3.33333 18.3334H15C15.442 18.3334 15.866 18.1578 16.1785 17.8452C16.4911 17.5326 16.6667 17.1087 16.6667 16.6667V5.83335L12.5 1.66669H5C4.55797 1.66669 4.13405 1.84228 3.82149 2.15484C3.50893 2.4674 3.33333 2.89133 3.33333 3.33335V6.66669M11.6667 1.66669V5.00002C11.6667 5.44205 11.8423 5.86597 12.1548 6.17853C12.4674 6.49109 12.8913 6.66669 13.3333 6.66669H16.6667M2.5 12.5L4.16667 14.1667L7.5 10.8334" stroke="#4D79FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="modal-doc-info">
                                        <span class="modal-doc-name">Счёт № СЧ4-0010422</span>
                                        <span class="modal-doc-size">0.7 МБ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-panel modal-panel--history">
                        <div class="modal-panel-header">
                            <div class="modal-panel-header-inner">
                                <span class="modal-panel-title">История заказа</span>
                            </div>
                        </div>

                        <div class="modal-panel-body">
                            <div class="modal-history-header">
                                <span class="modal-history-title">Комментарии</span>
                                <span class="modal-history-count">(2)</span>
                            </div>

                            <div class="modal-comment-new">
                                <textarea class="modal-comment-input" placeholder="Написать комментарий..."></textarea>
                                <div class="modal-comment-new-bottom">
                                    <div class="modal-comment-divider"></div>
                                    <button class="modal-comment-send">Отправить</button>
                                </div>
                            </div>

                            <div class="modal-comment">
                                <div class="modal-comment-header">
                                    <img class="modal-comment-avatar" src="https://placehold.co/30x30" alt="" />
                                    <span class="modal-comment-author">Ольга Ситникова</span>
                                </div>
                                <div class="modal-comment-bubble">
                                    <span>Товар собран, ждем машину</span>
                                </div>
                                <div class="modal-comment-time">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M14.5 8.25C14.5 11.7019 11.7019 14.5 8.25 14.5C4.79813 14.5 2 11.7019 2 8.25C2 4.79813 4.79813 2 8.25 2M14.375 7C14.2707 6.48834 14.1028 5.99176 13.875 5.52188M13.25 4.49938C12.8947 4.02559 12.4738 3.60471 12 3.24938M10.9775 2.625C10.5078 2.39729 10.0114 2.22932 9.5 2.125" stroke="#828282" stroke-width="1.5"/>
                                        <path d="M8.25 5.125V8.25L10.75 10.75" stroke="#828282" stroke-width="1.5"/>
                                    </svg>
                                    <span>14 марта 2026 · 10:30</span>
                                </div>
                            </div>

                            <div class="modal-comment">
                                <div class="modal-comment-header">
                                    <img class="modal-comment-avatar" src="https://placehold.co/30x30" alt="" />
                                    <span class="modal-comment-author">Сергей Кудряш</span>
                                </div>
                                <div class="modal-comment-bubble">
                                    <span>Клиент подтвердил самовывоз на понедельник</span>
                                </div>
                                <div class="modal-comment-time">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M14.5 8.25C14.5 11.7019 11.7019 14.5 8.25 14.5C4.79813 14.5 2 11.7019 2 8.25C2 4.79813 4.79813 2 8.25 2M14.375 7C14.2707 6.48834 14.1028 5.99176 13.875 5.52188M13.25 4.49938C12.8947 4.02559 12.4738 3.60471 12 3.24938M10.9775 2.625C10.5078 2.39729 10.0114 2.22932 9.5 2.125" stroke="#828282" stroke-width="1.5"/>
                                        <path d="M8.25 5.125V8.25L10.75 10.75" stroke="#828282" stroke-width="1.5"/>
                                    </svg>
                                    <span>12 марта 2026 · 15:50</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-actions">
                    <button class="modal-btn modal-btn--primary">
                        Взять в работу
                    </button>
                    <button class="modal-btn modal-btn--secondary">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M4.54547 4.54541V15.4545H1.81816V8.18178H0V5.45447H1.81816V4.54541H4.54547ZM13.4545 9.09084H16.2727C15.8182 6.54541 13.6364 4.54541 10.9091 4.54541C7.90906 4.54541 5.45453 6.99994 5.45453 9.99994C5.45453 12.9391 7.81059 15.3577 10.7273 15.4545H20V12.7272H10.9091C8.32031 12.6692 7.45887 9.82045 8.83594 8.2099C10.1986 6.6276 12.7746 7.1292 13.4616 9.11057" fill="#282828"/>
                        </svg>
                        Открыть в 1С
                    </button>
                    <button class="modal-btn modal-btn--secondary">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M8.74992 18.3333H4.99992C4.55789 18.3333 4.13397 18.1577 3.82141 17.8451C3.50885 17.5326 3.33325 17.1087 3.33325 16.6666V3.3333C3.33325 2.89127 3.50885 2.46735 3.82141 2.15479C4.13397 1.84223 4.55789 1.66663 4.99992 1.66663H11.6666M11.6666 1.66663C11.9307 1.66598 12.1923 1.71764 12.4363 1.81862C12.6803 1.9196 12.9019 2.0679 13.0883 2.25497L16.0783 5.24497C16.2653 5.43136 16.4136 5.65295 16.5146 5.89696C16.6156 6.14097 16.6672 6.40256 16.6666 6.66663M11.6666 1.66663V5.8333C11.6666 6.05431 11.7544 6.26627 11.9107 6.42255C12.0669 6.57883 12.2789 6.66663 12.4999 6.66663H16.6666M16.6666 6.66663V11.6666M11.6666 16.6666L13.3333 18.3333L16.6666 15" stroke="#282828" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Сформировать счет
                    </button>
                    <button class="modal-btn modal-btn--secondary">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M11.6666 1.66663H4.99992C4.55789 1.66663 4.13397 1.84222 3.82141 2.15478C3.50885 2.46734 3.33325 2.89127 3.33325 3.3333V16.6666C3.33325 17.1087 3.50885 17.5326 3.82141 17.8451C4.13397 18.1577 4.55789 18.3333 4.99992 18.3333H14.9999C15.4419 18.3333 15.8659 18.1577 16.1784 17.8451C16.491 17.5326 16.6666 17.1087 16.6666 16.6666V6.66663M11.6666 1.66663C11.9304 1.6662 12.1917 1.71796 12.4354 1.81894C12.6791 1.91991 12.9004 2.06809 13.0866 2.25496L16.0766 5.24496C16.264 5.43122 16.4126 5.65275 16.5138 5.89676C16.6151 6.14078 16.667 6.40244 16.6666 6.66663M11.6666 1.66663V5.83329C11.6666 6.05431 11.7544 6.26627 11.9107 6.42255C12.0669 6.57883 12.2789 6.66663 12.4999 6.66663L16.6666 6.66663M8.33325 7.49996H6.66659M13.3333 10.8333H6.66659M13.3333 14.1666H6.66659" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Расходная накладная
                    </button>
                    <button class="modal-btn modal-btn--secondary">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M12.5001 7.49996L7.50008 12.5M7.50008 7.49996L12.5001 12.5M18.3334 9.99996C18.3334 14.6023 14.6025 18.3333 10.0001 18.3333C5.39771 18.3333 1.66675 14.6023 1.66675 9.99996C1.66675 5.39759 5.39771 1.66663 10.0001 1.66663C14.6025 1.66663 18.3334 5.39759 18.3334 9.99996Z" stroke="#282828" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Вернуть заявку
                    </button>
                </div>
            </div>

            <div class="modal-close-wrap">
                <button class="modal-close" @click="$emit('close')">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M3.66602 3.33301L12.9993 12.6663M3.66602 12.6663L12.9993 3.33301" stroke="#222222" stroke-width="2" />
                    </svg>
                </button>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { reactive, watch } from 'vue'
import EditableField from '@/components/ui/EditableField.vue'

const props = defineProps({
    visible: { type: Boolean, default: false },
})

const emit = defineEmits(['close'])

const orderData = reactive({
    deliveryMethod: 'Самовывоз',
    reserveDates: '24.05.2024 — 27.05.2024',
    driver: 'Петров Иван',
    driverPhone: '+7 900 111-22-33',
    clientName: 'ООО «ТехноПром Инжиниринг»',
    clientInn: '7710140679',
    clientPhone: '+7 900 111-22-33',
    clientEmail: 'avdolodkina@trapeza.ru',
})

const onFieldSave = (field, value) => {
    console.log(`[OrderModal] Field "${field}" saved:`, value)
    // TODO: API call to save the field
}

watch(() => props.visible, (val) => {
    document.body.style.overflow = val ? 'hidden' : ''
})
</script>
