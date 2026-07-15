import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { orders as ordersApi } from '@/client'

export const useOrdersStore = defineStore('orders', () => {
    const allOrders = ref([])
    const currentOrder = ref(null)
    const loading = ref(false)
    const error = ref(null)

    const newOrders = computed(() =>
        allOrders.value.filter(o => o.request_status_color === 'green')
    )

    const inProgressOrders = computed(() =>
        allOrders.value.filter(o => o.request_status_color === 'orange')
    )

    const shippedOrders = computed(() =>
        allOrders.value.filter(o => o.request_status_color === 'purple')
    )

    async function fetchOrders(params = {}) {
        const isFirstLoad = allOrders.value.length === 0
        if (isFirstLoad) {
            loading.value = true
        }
        error.value = null
        try {
            const { data } = await ordersApi.getList(params)
            allOrders.value = data.data
        } catch (e) {
            error.value = e.response?.data?.message || 'Ошибка загрузки заявок'
        } finally {
            if (isFirstLoad) {
                loading.value = false
            }
        }
    }

    async function fetchOrder(id) {
        loading.value = true
        error.value = null
        try {
            const { data } = await ordersApi.getById(id)
            currentOrder.value = data.data
        } catch (e) {
            error.value = e.response?.data?.message || 'Ошибка загрузки заказа'
        } finally {
            loading.value = false
        }
    }

    async function takeOrder(id) {
        const { data } = await ordersApi.take(id)
        currentOrder.value = data.data
        await fetchOrders()
        return data.data
    }

    async function returnOrder(id, comment) {
        const { data } = await ordersApi.return(id, comment)
        currentOrder.value = data.data
        await fetchOrders()
        return data.data
    }

    async function updateField(id, field, value) {
        const { data } = await ordersApi.updateField(id, field, value)
        currentOrder.value = data.data
        await fetchOrders()
        return data.data
    }

    async function addComment(id, body, isInternal = false) {
        const { data } = await ordersApi.addComment(id, body, isInternal)
        currentOrder.value = data.data
        return data.data
    }

    function $reset() {
        allOrders.value = []
        currentOrder.value = null
        loading.value = false
        error.value = null
    }

    return {
        allOrders,
        currentOrder,
        loading,
        error,
        newOrders,
        inProgressOrders,
        shippedOrders,
        fetchOrders,
        fetchOrder,
        takeOrder,
        returnOrder,
        updateField,
        addComment,
        $reset,
    }
})
