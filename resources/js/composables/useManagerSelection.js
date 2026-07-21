import { computed } from 'vue'
import { useRouteQuery } from './useRouteQuery'
import { useAuth } from './useAuth'
import { useManagersStore } from '@/stores/managers'

export function useManagerSelection() {
    const { user } = useAuth()
    const managersStore = useManagersStore()

    const selectedIds = useRouteQuery('manager_ids', [], {
        transform: (v) => v,
        parse: (v) => (Array.isArray(v) ? v : [v]).map(Number),
    })

    const selectedLabel = computed(() => {
        if (selectedIds.value.length === 1) {
            if (selectedIds.value[0] === user.value?.id) {
                return 'Только я'
            }
            const m = managersStore.items.find(i => i.id === selectedIds.value[0])
            return m ? m.name : ''
        }
        return ''
    })

    const showCounter = computed(() => {
        return selectedIds.value.length > 1 && selectedIds.value.length < managersStore.items.length
    })

    return { selectedIds, selectedLabel, showCounter }
}
