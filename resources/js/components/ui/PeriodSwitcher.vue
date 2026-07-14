<template>
    <div ref="containerRef" class="stats-period-switcher">
        <button
            v-for="p in periods"
            :key="p.key"
            class="stats-period-btn"
            :class="{ active: modelValue === p.key }"
            @click="onPeriodClick(p.key, $event.currentTarget)"
        >
            <span v-if="p.key === 'custom'" class="stats-period-btn-inner">
                {{ customLabel }}
                <span class="stats-period-chevron" :class="{ 'stats-period-chevron--open': showPicker }">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <mask :id="maskId" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="20" height="20">
                            <rect x="20" width="20" height="20" transform="rotate(90 20 0)" fill="#D9D9D9"/>
                        </mask>
                        <g :mask="`url(#${maskId})`">
                            <path d="M9.99967 10.8748L13.4163 7.45817C13.5691 7.30539 13.7462 7.229 13.9476 7.229C14.149 7.229 14.3261 7.30539 14.4788 7.45817C14.6316 7.61095 14.708 7.78803 14.708 7.98942C14.708 8.19081 14.6293 8.37024 14.4718 8.52771L10.5205 12.479C10.4455 12.5484 10.3643 12.6005 10.2768 12.6353C10.1893 12.67 10.0955 12.6873 9.99551 12.6873C9.89551 12.6873 9.80176 12.67 9.71426 12.6353C9.62676 12.6005 9.54829 12.5484 9.47884 12.479L5.52755 8.52771C5.37008 8.37024 5.29481 8.19428 5.30176 7.99984C5.3087 7.80539 5.38856 7.63178 5.54134 7.479C5.69412 7.32623 5.8712 7.24984 6.07259 7.24984C6.27398 7.24984 6.45106 7.32623 6.60384 7.479L9.99967 10.8748Z" fill="#282828"/>
                        </g>
                    </svg>
                </span>
            </span>
            <template v-else>{{ p.label }}</template>
        </button>

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
            @apply="apply"
        />
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { format } from 'date-fns'
import { ru } from 'date-fns/locale'
import DatePickerPopup from './DatePicker/DatePickerPopup.vue'
import { useDatePicker } from './DatePicker/useDatePicker'
import { useClickOutside } from '@/composables/useClickOutside'

const props = defineProps({
    modelValue: { type: String, default: 'today' },
    dateRange: { type: Object, default: null },
})

const emit = defineEmits(['update:modelValue', 'update:dateRange'])

const containerRef = ref(null)

let idCounter = 0
const maskId = `mask-period-${++idCounter}-${Date.now()}`

const periods = [
    { key: 'yesterday', label: 'Вчера' },
    { key: 'today', label: 'Сегодня' },
    { key: 'week', label: 'Неделя' },
    { key: 'month', label: 'Месяц' },
    { key: 'custom', label: 'Выбрать период' },
]

const customLabel = computed(() => {
    if (props.dateRange?.start && props.dateRange?.end) {
        const start = format(props.dateRange.start, 'd MMMM', { locale: ru })
        const end = format(props.dateRange.end, 'd MMMM', { locale: ru })
        return `${start} — ${end}`
    }
    return 'Выбрать период'
})

const {
    showPicker, selectedStart, selectedEnd, hoverDate, popupPosition,
    leftMonth, leftYear, rightMonth, rightYear,
    open, close, goPrev, goNext, onSelectDate, onHoverDate, getResult,
} = useDatePicker()

function onPeriodClick(key, el) {
    if (key === 'custom') {
        if (showPicker.value) {
            close()
        } else {
            open(el, props.dateRange)
        }
    } else {
        close()
        emit('update:modelValue', key)
    }
}

function apply() {
    const result = getResult()
    emit('update:modelValue', 'custom')
    emit('update:dateRange', result)
    close()
}

useClickOutside(containerRef, () => {
    if (showPicker.value) close()
}, {
    ignore: (e) => e.target.closest('.dp'),
})
</script>
