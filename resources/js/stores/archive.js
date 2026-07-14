import { defineStore } from 'pinia'
import { ref } from 'vue'
import { archive as archiveApi } from '../client'

export const useArchiveStore = defineStore('archive', () => {
    const items = ref([])
    const loading = ref(false)
    const error = ref(null)

    async function fetchArchive(params = {}) {
        loading.value = true
        error.value = null
        try {
            const { data } = await archiveApi.getList(params)
            items.value = data.data
        } catch (e) {
            error.value = e.response?.data?.message || 'Ошибка загрузки архива'
        } finally {
            loading.value = false
        }
    }

    function $reset() {
        items.value = []
        loading.value = false
        error.value = null
    }

    return {
        items,
        loading,
        error,
        fetchArchive,
        $reset,
    }
})
