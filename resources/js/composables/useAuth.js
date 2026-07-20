import { computed } from 'vue'
import { useAccountStore } from '@/stores/account'

export function useAuth() {
    const accountStore = useAccountStore()

    return {
        user: computed(() => accountStore.user),
    }
}
