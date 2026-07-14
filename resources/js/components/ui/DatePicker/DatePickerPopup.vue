<template>
    <Teleport to="body">
        <div v-if="visible" class="dp-overlay" @click.self="$emit('close')">
            <div class="dp" :style="popupPosition">
                <div class="dp__calendars">
                    <CalendarMonth
                        :year="leftYear"
                        :month="leftMonth"
                        :selected-start="selectedStart"
                        :selected-end="selectedEnd"
                        :hover-date="hoverDate"
                        :show-left-arrow="true"
                        :show-right-arrow="false"
                        @select="$emit('select', $event)"
                        @hover="$emit('hover', $event)"
                        @prev="$emit('prev')"
                    />

                    <div class="dp__divider" />

                    <CalendarMonth
                        :year="rightYear"
                        :month="rightMonth"
                        :selected-start="selectedStart"
                        :selected-end="selectedEnd"
                        :hover-date="hoverDate"
                        :show-left-arrow="false"
                        :show-right-arrow="true"
                        @select="$emit('select', $event)"
                        @hover="$emit('hover', $event)"
                        @next="$emit('next')"
                    />
                </div>

                <div class="dp__line" />

                <div class="dp__footer">
                    <button class="dp__apply" @click="$emit('apply')">Применить</button>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import CalendarMonth from './CalendarMonth.vue'

defineProps({
    visible: { type: Boolean, default: false },
    leftYear: { type: Number, required: true },
    leftMonth: { type: Number, required: true },
    rightYear: { type: Number, required: true },
    rightMonth: { type: Number, required: true },
    selectedStart: { type: Date, default: null },
    selectedEnd: { type: Date, default: null },
    hoverDate: { type: Date, default: null },
    popupPosition: { type: Object, default: () => ({}) },
})

defineEmits(['close', 'select', 'hover', 'prev', 'next', 'apply'])
</script>
