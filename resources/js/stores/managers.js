import { defineStore } from 'pinia'
import { ref } from 'vue'
import { managers as managersApi } from '../client'

export const useManagersStore = defineStore('managers', () => {
    const items = ref([])
    const loading = ref(false)

    async function fetchManagers() {
        loading.value = true
        try {
            const { data } = await managersApi.getList()
            items.value = data.data
        } catch (e) {
            // silent
        } finally {
            loading.value = false
        }
    }

    function $reset() {
        items.value = []
        loading.value = false
    }

    return {
        items,
        loading,
        fetchManagers,
        $reset,
    }
})
