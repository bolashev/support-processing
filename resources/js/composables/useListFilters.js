import { ref, computed } from 'vue'
import { useSort } from './useSort'

export function useListFilters(items, options = {}) {
    const { defaultPeriod = 'today', defaultSortDirection = 'desc' } = options

    const period = ref(defaultPeriod)
    const dateRange = ref(null)
    const selectedManagerIds = ref([])
    const searchValue = ref('')

    const { sortKey, sortDirection, toggleSort, reset: resetSort } = useSort(defaultSortDirection)

    const filteredItems = computed(() => {
        let result = [...items.value]

        if (selectedManagerIds.value.length > 0) {
            result = result.filter(item =>
                selectedManagerIds.value.includes(item.managerId ?? item.id)
            )
        }

        if (searchValue.value) {
            const q = searchValue.value.toLowerCase()
            result = result.filter(item => {
                const searchFields = [
                    item.number,
                    item.name,
                    item.manager,
                    item.status,
                ].filter(Boolean)
                return searchFields.some(f => f.toLowerCase().includes(q))
            })
        }

        if (sortKey.value) {
            result.sort((a, b) => {
                const valA = a[sortKey.value]
                const valB = b[sortKey.value]

                if (typeof valA === 'string') {
                    return sortDirection.value === 'asc'
                        ? valA.localeCompare(valB)
                        : valB.localeCompare(valA)
                }
                return sortDirection.value === 'asc' ? valA - valB : valB - valA
            })
        }

        return result
    })

    function reset() {
        period.value = defaultPeriod
        dateRange.value = null
        selectedManagerIds.value = []
        searchValue.value = ''
        resetSort()
    }

    return {
        period,
        dateRange,
        selectedManagerIds,
        searchValue,
        sortKey,
        sortDirection,
        toggleSort,
        filteredItems,
        reset,
    }
}
