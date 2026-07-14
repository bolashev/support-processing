import { defineStore } from 'pinia'
import { ref } from 'vue'
import { dashboard as dashboardApi } from '../client'

export const useDashboardStore = defineStore('dashboard', () => {
    const counters = ref({
        new_count: 0,
        in_progress_count: 0,
        shipped_count: 0,
    })
    const loading = ref(false)

    async function fetchCounters() {
        loading.value = true
        try {
            const { data } = await dashboardApi.getCounters()
            counters.value = data.data
        } catch (e) {
            // silent
        } finally {
            loading.value = false
        }
    }

    function $reset() {
        counters.value = { new_count: 0, in_progress_count: 0, shipped_count: 0 }
        loading.value = false
    }

    return {
        counters,
        loading,
        fetchCounters,
        $reset,
    }
})
