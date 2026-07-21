import { defineStore } from 'pinia'
import { computed, reactive, ref } from 'vue'
import { orders as ordersApi } from '@/client'

const COLUMN_KEYS = ['new', 'inProgress', 'shipped']

const COLUMN_TO_STATUS = {
    new: 'new',
    inProgress: 'in_progress',
    shipped: 'completed',
}

const EMPTY_PAGINATION = { page: 1, lastPage: 1, total: 0 }

function createColumnState() {
    return reactive({
        items: [],
        loading: false,
        loadingMore: false,
        error: null,
        pagination: { ...EMPTY_PAGINATION },
        lastParams: {},
    })
}

function buildParams(statusKey, params = {}) {
    const base = {
        request_status: COLUMN_TO_STATUS[statusKey],
        per_page: 20,
    }
    if (params.search) {
        base.search = params.search
    }
    if (params.manager_ids && params.manager_ids.length > 0) {
        base.manager_ids = params.manager_ids
    }
    if (statusKey === 'shipped' && params.shipped_sort) {
        base.shipped_sort = params.shipped_sort
    }
    return base
}

export const useOrdersStore = defineStore('orders', () => {
    const columns = {
        new: createColumnState(),
        inProgress: createColumnState(),
        shipped: createColumnState(),
    }

    const currentOrder = ref(null)
    const loadingOrder = ref(false)
    const error = ref(null)

    const newOrders = computed(() => columns.new.items)
    const inProgressOrders = computed(() => columns.inProgress.items)
    const shippedOrders = computed(() => columns.shipped.items)

    const newTotal = computed(() => columns.new.pagination.total)
    const inProgressTotal = computed(() => columns.inProgress.pagination.total)
    const shippedTotal = computed(() => columns.shipped.pagination.total)

    const newHasMore = computed(() => columns.new.pagination.page < columns.new.pagination.lastPage)
    const inProgressHasMore = computed(() => columns.inProgress.pagination.page < columns.inProgress.pagination.lastPage)
    const shippedHasMore = computed(() => columns.shipped.pagination.page < columns.shipped.pagination.lastPage)

    const anyInitialLoading = computed(() =>
        columns.new.loading || columns.inProgress.loading || columns.shipped.loading
    )
    const allEmpty = computed(() =>
        columns.new.items.length === 0
        && columns.inProgress.items.length === 0
        && columns.shipped.items.length === 0
    )

    function applyMeta(state, meta) {
        if (!meta) return
        state.pagination = {
            page: meta.current_page ?? state.pagination.page,
            lastPage: meta.last_page ?? state.pagination.lastPage,
            total: meta.total ?? state.pagination.total,
        }
    }

    async function fetchColumn(statusKey, params = {}, { append = false } = {}) {
        const state = columns[statusKey]
        if (!state) return

        if (append) {
            if (state.loading || state.loadingMore) return
            if (state.pagination.page >= state.pagination.lastPage) return
            state.loadingMore = true
        } else {
            state.loading = true
        }
        state.error = null

        const requestParams = buildParams(statusKey, params)
        if (append) {
            requestParams.page = state.pagination.page + 1
        } else {
            requestParams.page = 1
        }

        try {
            const { data } = await ordersApi.getList(requestParams)
            if (append) {
                state.items = [...state.items, ...data.data]
            } else {
                state.items = data.data
                state.lastParams = { ...params }
            }
            applyMeta(state, data.meta)
        } catch (e) {
            state.error = e.response?.data?.message || 'Ошибка загрузки заказов'
        } finally {
            state.loading = false
            state.loadingMore = false
        }
    }

    async function fetchAll(params = {}) {
        await Promise.all(COLUMN_KEYS.map((key) => fetchColumn(key, params)))
    }

    function reset() {
        for (const key of COLUMN_KEYS) {
            const state = columns[key]
            state.items = []
            state.pagination = { ...EMPTY_PAGINATION }
            state.error = null
        }
    }

    function refreshColumns(params = {}) {
        const refreshed = {}
        for (const key of COLUMN_KEYS) {
            const state = columns[key]
            state.items = []
            state.pagination = { ...EMPTY_PAGINATION }
            state.error = null
            refreshed[key] = fetchColumn(key, params)
        }
        return Promise.all(Object.values(refreshed))
    }

    async function fetchOrder(id) {
        loadingOrder.value = true
        error.value = null
        try {
            const { data } = await ordersApi.getById(id)
            currentOrder.value = data.data
        } catch (e) {
            error.value = e.response?.data?.message || 'Ошибка загрузки заказа'
        } finally {
            loadingOrder.value = false
        }
    }

    async function takeOrder(id) {
        const { data } = await ordersApi.take(id)
        currentOrder.value = data.data
        await refreshColumns()
        return data.data
    }

    async function returnOrder(id, comment) {
        const { data } = await ordersApi.return(id, comment)
        currentOrder.value = data.data
        await refreshColumns()
        return data.data
    }

    async function updateField(id, field, value) {
        await ordersApi.updateField(id, field, value)
        if (currentOrder.value && currentOrder.value.id === id) {
            if (field === 'reserve_range') {
                if (value) {
                    const parts = value.split(' — ')
                    currentOrder.value.reserve_date_start_at = parts[0] ? parts[0] + ' 00:00:00' : null
                    currentOrder.value.reserve_date_end_at = (parts[1] || parts[0]) ? (parts[1] || parts[0]) + ' 23:59:59' : null
                } else {
                    currentOrder.value.reserve_date_start_at = null
                    currentOrder.value.reserve_date_end_at = null
                }
            } else {
                currentOrder.value[field] = value
            }
        }
    }

    async function addComment(id, body, isInternal = false) {
        const { data } = await ordersApi.addComment(id, body, isInternal)
        currentOrder.value = data.data
        return data.data
    }

    function $reset() {
        reset()
        currentOrder.value = null
        loadingOrder.value = false
        error.value = null
    }

    return {
        columns,
        newOrders,
        inProgressOrders,
        shippedOrders,
        newTotal,
        inProgressTotal,
        shippedTotal,
        newHasMore,
        inProgressHasMore,
        shippedHasMore,
        anyInitialLoading,
        allEmpty,
        currentOrder,
        loadingOrder,
        error,
        fetchColumn,
        fetchAll,
        reset,
        refreshColumns,
        fetchOrder,
        takeOrder,
        returnOrder,
        updateField,
        addComment,
        $reset,
    }
})
