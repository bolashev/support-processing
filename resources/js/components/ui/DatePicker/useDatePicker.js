import { ref, computed, watch, nextTick, onMounted, onBeforeUnmount } from 'vue'
import { addMonths, subMonths } from 'date-fns'

export function useDatePicker() {
    const showPicker = ref(false)
    const baseDate = ref(new Date())
    const selectedStart = ref(null)
    const selectedEnd = ref(null)
    const hoverDate = ref(null)
    const popupPosition = ref({})
    let triggerEl = null

    const leftMonth = computed(() => baseDate.value.getMonth())
    const leftYear = computed(() => baseDate.value.getFullYear())

    const rightDate = computed(() => addMonths(baseDate.value, 1))
    const rightMonth = computed(() => rightDate.value.getMonth())
    const rightYear = computed(() => rightDate.value.getFullYear())

    async function open(el, initialValue = null) {
        if (initialValue?.start) {
            selectedStart.value = new Date(initialValue.start)
            selectedEnd.value = initialValue.end ? new Date(initialValue.end) : null
            baseDate.value = new Date(initialValue.start)
        } else {
            selectedStart.value = null
            selectedEnd.value = null
            baseDate.value = new Date()
        }
        hoverDate.value = null
        triggerEl = el
        showPicker.value = true
        await nextTick()
        updatePosition()
    }

    function close() {
        showPicker.value = false
        triggerEl = null
    }

    function updatePosition() {
        if (!triggerEl) return
        const rect = triggerEl.getBoundingClientRect()
        popupPosition.value = {
            position: 'fixed',
            top: `${rect.bottom + 8}px`,
            left: `${rect.left}px`,
            zIndex: 1001,
        }
    }

    function onScroll() {
        if (showPicker.value) {
            updatePosition()
        }
    }

    onMounted(() => window.addEventListener('scroll', onScroll, true))
    onBeforeUnmount(() => window.removeEventListener('scroll', onScroll, true))

    function goPrev() {
        baseDate.value = subMonths(baseDate.value, 1)
    }

    function goNext() {
        baseDate.value = addMonths(baseDate.value, 1)
    }

    function onSelectDate(date) {
        if (!selectedStart.value || (selectedStart.value && selectedEnd.value)) {
            selectedStart.value = date
            selectedEnd.value = null
        } else {
            if (date < selectedStart.value) {
                selectedEnd.value = selectedStart.value
                selectedStart.value = date
            } else {
                selectedEnd.value = date
            }
        }
    }

    function onHoverDate(date) {
        hoverDate.value = date
    }

    function getResult() {
        return {
            start: selectedStart.value,
            end: selectedEnd.value || selectedStart.value,
        }
    }

    return {
        showPicker,
        baseDate,
        selectedStart,
        selectedEnd,
        hoverDate,
        popupPosition,
        leftMonth,
        leftYear,
        rightMonth,
        rightYear,
        open,
        close,
        goPrev,
        goNext,
        onSelectDate,
        onHoverDate,
        getResult,
    }
}
