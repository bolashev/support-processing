<template>
    <div class="nav-card-filter">
        <div class="filter-row">
            <ManagerDropdown
                :items="managersStore.items"
                v-model:selected-ids="selectedIds"
                prefix="Менеджер заказа:"
                :label="selectedLabel"
                :show-sep="showCounter"
                :show-count="showCounter"
                :current-user-id="user?.id"
            />
        </div>
        <SearchInput v-model="search" />
    </div>
</template>

<script setup>
import { onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useManagersStore } from '@/stores/managers'
import { useOrdersStore } from '@/stores/orders'
import { useAuth } from '@/composables/useAuth'
import { useRouteQuery } from '@/composables/useRouteQuery'
import { useManagerSelection } from '@/composables/useManagerSelection'
import SearchInput from '@/components/ui/SearchInput.vue'
import ManagerDropdown from '@/components/ui/ManagerDropdown.vue'

const route = useRoute()
const router = useRouter()
const managersStore = useManagersStore()
const ordersStore = useOrdersStore()
const { user } = useAuth()

const search = useRouteQuery('search', '', { debounce: 300 })

const { selectedIds, selectedLabel, showCounter } = useManagerSelection()

onMounted(() => {
    managersStore.fetchManagers()
})

watch(
    [selectedIds, () => route.query.search, () => route.query.shipped_sort],
    () => {
        const params = {}
        if (selectedIds.value.length > 0) {
            params.manager_ids = selectedIds.value
        }
        if (route.query.search) {
            params.search = route.query.search
        }
        if (route.query.shipped_sort) {
            params.shipped_sort = route.query.shipped_sort
        }
        ordersStore.fetchAll(params)
    },
    { immediate: true },
)
</script>
