<template>
    <div class="nav-card-filter">
        <div class="filter-row">
            <ManagerDropdown
                :items="managersStore.items"
                v-model:selected-ids="selectedIds"
                prefix="Менеджер заявки:"
                :label="selectedLabel"
                :show-sep="showCounter"
                :show-count="showCounter"
                :current-user-id="user?.id"
            />
        </div>
        <SearchInput v-model="localSearch" />
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useManagersStore } from '@/stores/managers'
import { useOrdersStore } from '@/stores/orders'
import { useAuth } from '@/composables/useAuth'
import SearchInput from '@/components/ui/SearchInput.vue'
import ManagerDropdown from '@/components/ui/ManagerDropdown.vue'

const route = useRoute()
const router = useRouter()
const managersStore = useManagersStore()
const ordersStore = useOrdersStore()
const { user } = useAuth()

const selectedIds = computed({
    get() {
        const ids = route.query.manager_ids
        if (!ids) return []
        return (Array.isArray(ids) ? ids : [ids]).map(Number)
    },
    set(val) {
        const query = { ...route.query }
        delete query.manager_ids
        if (val.length > 0) {
            query.manager_ids = val
        }
        router.replace({ query })
    },
})

const localSearch = ref(route.query.search || '')
let searchTimeout = null

watch(() => route.query.search, (val) => {
    localSearch.value = val || ''
})

watch(localSearch, (val) => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        const query = { ...route.query }
        if (val) {
            query.search = val
        } else {
            delete query.search
        }
        router.replace({ query })
    }, 300)
})

onMounted(() => {
    managersStore.fetchManagers()
})

watch([selectedIds, () => route.query.search, () => route.query.shipped_sort], () => {
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
    ordersStore.fetchOrders(params)
}, { immediate: true })

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
</script>
