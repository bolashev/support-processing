import { ref } from 'vue'

export function useSort(defaultDirection = 'desc') {
    const sortKey = ref(null)
    const sortDirection = ref(defaultDirection)

    function toggleSort(key) {
        if (sortKey.value === key) {
            sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
        } else {
            sortKey.value = key
            sortDirection.value = defaultDirection
        }
    }

    function reset() {
        sortKey.value = null
        sortDirection.value = defaultDirection
    }

    return { sortKey, sortDirection, toggleSort, reset }
}
