import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useToastsStore = defineStore('toasts', () => {
    const items = ref([])
    let nextId = 0

    function add(message, type = 'error', duration = 5000) {
        const id = nextId++
        items.value.push({ id, message, type })

        if (duration > 0) {
            setTimeout(() => remove(id), duration)
        }

        return id
    }

    function error(message, duration) {
        return add(message, 'error', duration)
    }

    function success(message, duration) {
        return add(message, 'success', duration)
    }

    function warning(message, duration) {
        return add(message, 'warning', duration)
    }

    function remove(id) {
        items.value = items.value.filter(t => t.id !== id)
    }

    function $reset() {
        items.value = []
    }

    return {
        items,
        add,
        error,
        success,
        warning,
        remove,
        $reset,
    }
})
