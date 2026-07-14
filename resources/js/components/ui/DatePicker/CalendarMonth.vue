<template>
    <div class="cal-month">
        <div class="cal-month__header">
            <button
                v-if="showLeftArrow"
                class="cal-month__arrow"
                @click="$emit('prev')"
            >
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M15.7049 7.41L14.2949 6L8.29492 12L14.2949 18L15.7049 16.59L11.1249 12L15.7049 7.41Z" fill="rgba(0,0,0,0.56)" />
                </svg>
            </button>
            <span class="cal-month__title">{{ monthLabel }}</span>
            <button
                v-if="showRightArrow"
                class="cal-month__arrow"
                @click="$emit('next')"
            >
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M8.29508 16.59L9.70508 18L15.7051 12L9.70508 6L8.29508 7.41L12.8751 12L8.29508 16.59Z" fill="rgba(0,0,0,0.56)" />
                </svg>
            </button>
        </div>

        <div class="cal-month__weekdays">
            <span
                v-for="day in weekdays"
                :key="day"
                class="cal-month__weekday"
                :class="{ 'cal-month__weekday--weekend': day === 'Сб.' || day === 'Вс.' }"
            >{{ day }}</span>
        </div>

        <div class="cal-month__grid">
            <div
                v-for="(week, wi) in weeks"
                :key="wi"
                class="cal-month__week"
            >
                <button
                    v-for="(cell, ci) in week"
                    :key="ci"
                    class="cal-month__day"
                    :class="dayClasses(cell)"
                    :disabled="cell.disabled"
                    @click="cell.date && $emit('select', cell.date)"
                    @mouseenter="cell.date && $emit('hover', cell.date)"
                    @mouseleave="$emit('hover', null)"
                >
                    {{ cell.day || '' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import {
    startOfMonth,
    endOfMonth,
    getDay,
    getDaysInMonth,
    format,
    isSameDay,
    isBefore,
    isAfter,
    addMonths,
} from 'date-fns'
import { ru } from 'date-fns/locale'

const props = defineProps({
    year: { type: Number, required: true },
    month: { type: Number, required: true },
    selectedStart: { type: Date, default: null },
    selectedEnd: { type: Date, default: null },
    hoverDate: { type: Date, default: null },
    showLeftArrow: { type: Boolean, default: false },
    showRightArrow: { type: Boolean, default: false },
    minDate: { type: Date, default: null },
})

defineEmits(['select', 'hover', 'prev', 'next'])

const today = new Date()

const weekdays = ['Пн.', 'Вт.', 'Ср.', 'Чт.', 'Пт.', 'Сб.', 'Вс.']

const monthLabel = computed(() => {
    return format(new Date(props.year, props.month), 'LLLL yyyy', { locale: ru })
        .replace(/^\w/, c => c.toUpperCase())
})

const weeks = computed(() => {
    const firstDay = new Date(props.year, props.month, 1)
    const daysCount = getDaysInMonth(firstDay)
    let startDow = getDay(firstDay)
    startDow = (startDow + 6) % 7 // Convert: 0=Mon, ..., 6=Sun

    const cells = []

    for (let i = 0; i < startDow; i++) {
        cells.push({ day: null, date: null })
    }

    for (let d = 1; d <= daysCount; d++) {
        const date = new Date(props.year, props.month, d)
        const isWeekend = getDay(date) === 0 || getDay(date) === 6
        const isPast = isBefore(date, today) && !isSameDay(date, today)
        const disabled = props.minDate && isBefore(date, props.minDate)

        cells.push({
            day: d,
            date,
            isWeekend,
            isPast,
            disabled,
        })
    }

    while (cells.length % 7 !== 0) {
        cells.push({ day: null, date: null })
    }

    const result = []
    for (let i = 0; i < cells.length; i += 7) {
        result.push(cells.slice(i, i + 7))
    }

    return result
})

function dayClasses(cell) {
    if (!cell.date) return {}

    const date = cell.date
    const isToday = isSameDay(date, today)
    const isWeekend = cell.isWeekend
    const isDisabled = cell.disabled

    const isStart = props.selectedStart && isSameDay(date, props.selectedStart)
    const isEnd = props.selectedEnd && isSameDay(date, props.selectedEnd)
    const isInRange = isDateInRange(date)
    const isHoverRange = isDateInHoverRange(date)

    return {
        'cal-month__day--weekend': isWeekend && !isStart && !isEnd,
        'cal-month__day--today': isToday,
        'cal-month__day--selected': isStart || isEnd,
        'cal-month__day--in-range': isInRange,
        'cal-month__day--hover-range': isHoverRange && !isInRange,
        'cal-month__day--disabled': isDisabled,
        'cal-month__day--start': isStart,
        'cal-month__day--end': isEnd,
    }
}

function isDateInRange(date) {
    if (!props.selectedStart || !props.selectedEnd) return false
    const start = isBefore(props.selectedStart, props.selectedEnd) ? props.selectedStart : props.selectedEnd
    const end = isAfter(props.selectedEnd, props.selectedStart) ? props.selectedEnd : props.selectedStart
    return (isSameDay(date, start) || isAfter(date, start)) && (isSameDay(date, end) || isBefore(date, end))
}

function isDateInHoverRange(date) {
    if (!props.selectedStart || props.selectedEnd || !props.hoverDate) return false
    const start = isBefore(props.selectedStart, props.hoverDate) ? props.selectedStart : props.hoverDate
    const end = isAfter(props.hoverDate, props.selectedStart) ? props.hoverDate : props.selectedStart
    return (isSameDay(date, start) || isAfter(date, start)) && (isSameDay(date, end) || isBefore(date, end))
}
</script>
