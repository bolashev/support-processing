import { defineStore } from 'pinia'
import { ref } from 'vue'
import { archive as archiveApi } from '@/client'

const EMPTY_PAGINATION = { page: 1, lastPage: 1, perPage: 0, total: 0 }

export const useArchiveStore = defineStore('archive', () => {
    const items = ref([])
    const hasAny = ref(false)
    const loading = ref(false)
    const loadingMore = ref(false)
    const error = ref(null)
    const pagination = ref({ ...EMPTY_PAGINATION })
    const lastParams = ref({})

    function reset() {
        items.value = []
        pagination.value = { ...EMPTY_PAGINATION }
    }

    async function fetchArchive(params = {}, { append = false } = {}) {
        if (append) {
            loadingMore.value = true
        } else {
            loading.value = true
        }
        error.value = null
        try {
            const requestParams = {
                page: append ? pagination.value.page + 1 : 1,
                ...params,
            }
            const { data } = await archiveApi.getList(requestParams)
            if (append) {
                items.value = [...items.value, ...data.data]
            } else {
                items.value = data.data
                lastParams.value = params
            }
            hasAny.value = Boolean(data.meta?.has_any)
            if (data.meta) {
                pagination.value = {
                    page: data.meta.current_page ?? 1,
                    lastPage: data.meta.last_page ?? 1,
                    perPage: data.meta.per_page ?? 0,
                    total: data.meta.total ?? 0,
                }
            }
        } catch (e) {
            error.value = e.response?.data?.message || 'Ошибка загрузки архива'
        } finally {
            loading.value = false
            loadingMore.value = false
        }
    }

    function $reset() {
        reset()
        hasAny.value = false
        loading.value = false
        loadingMore.value = false
        error.value = null
        lastParams.value = {}
    }

    return {
        items,
        hasAny,
        loading,
        loadingMore,
        error,
        pagination,
        lastParams,
        fetchArchive,
        reset,
        $reset,
    }
})
