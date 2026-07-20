import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { account as accountApi } from '@/client'

export const useAccountStore = defineStore('account', () => {
    const user = ref(null)
    const loading = ref(false)

    const isAdmin = computed(() => user.value?.roles?.includes('admin') ?? false)
    const isSupportManager = computed(() => user.value?.roles?.includes('support_manager') ?? false)
    const canManageOrders = computed(() => isSupportManager.value && !isAdmin.value)

    async function fetchAccount() {
        loading.value = true
        try {
            const { data } = await accountApi.get()
            user.value = data.data
        } catch (e) {
            // silent
        } finally {
            loading.value = false
        }
    }

    function $reset() {
        user.value = null
        loading.value = false
    }

    return {
        user,
        loading,
        isAdmin,
        isSupportManager,
        canManageOrders,
        fetchAccount,
        $reset,
    }
})
