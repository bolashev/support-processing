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

            <PeriodSwitcher v-model="period" v-model:date-range="dateRange" />
        </div>
        <SearchInput v-model="search" placeholder="Поиск по архиву..." />
    </div>
</template>

<script setup>
import { computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useArchiveStore } from '@/stores/archive'
import { useManagersStore } from '@/stores/managers'
import { useAuth } from '@/composables/useAuth'
import { useRouteQuery } from '@/composables/useRouteQuery'
import { useManagerSelection } from '@/composables/useManagerSelection'
import SearchInput from '@/components/ui/SearchInput.vue'
import ManagerDropdown from '@/components/ui/ManagerDropdown.vue'
import PeriodSwitcher from '@/components/ui/PeriodSwitcher.vue'

const route = useRoute()
const router = useRouter()
const archiveStore = useArchiveStore()
const managersStore = useManagersStore()
const { user } = useAuth()

const DEFAULT_PERIOD = 'today'

const fmtLocal = (d) => {
    const y = d.getFullYear()
    const m = String(d.getMonth() + 1).padStart(2, '0')
    const day = String(d.getDate()).padStart(2, '0')
    return `${y}-${m}-${day}`
}

const search = useRouteQuery('search', '', { debounce: 300 })

const { selectedIds, selectedLabel, showCounter } = useManagerSelection()

const period = useRouteQuery('period', DEFAULT_PERIOD, {
    clearOnDefault: true,
    clearKeys: ['date_from', 'date_to'],
})

const dateFrom = useRouteQuery('date_from', null)
const dateTo = useRouteQuery('date_to', null)

const dateRange = computed({
    get() {
        if (period.value !== 'custom') return null
        if (!dateFrom.value || !dateTo.value) return null
        return {
            start: new Date(dateFrom.value),
            end: new Date(dateTo.value),
        }
    },
    set(val) {
        const query = { ...route.query }
        if (val?.start && val?.end) {
            query.period = 'custom'
            query.date_from = fmtLocal(val.start)
            query.date_to = fmtLocal(val.end)
        } else {
            delete query.date_from
            delete query.date_to
            if (query.period === 'custom' || !query.period) {
                query.period = DEFAULT_PERIOD
                if (query.period === DEFAULT_PERIOD) {
                    delete query.period
                }
            }
        }
        router.replace({ query })
    },
})

onMounted(() => {
    managersStore.fetchManagers(currentManagerParams())
})

watch(
    [() => route.query.period, () => route.query.date_from, () => route.query.date_to],
    () => {
        managersStore.fetchManagers(currentManagerParams())
    },
    { immediate: true },
)

function currentManagerParams() {
    const params = { shipped: 1, period: route.query.period || DEFAULT_PERIOD }
    if (params.period === 'custom') {
        if (route.query.date_from) params.date_from = route.query.date_from
        if (route.query.date_to) params.date_to = route.query.date_to
    }
    return params
}

watch(
    [
        selectedIds,
        () => route.query.search,
        () => route.query.period,
        () => route.query.date_from,
        () => route.query.date_to,
        () => route.query.sort,
        () => route.query.dir,
    ],
    () => {
        const params = {}
        if (selectedIds.value.length > 0) {
            params.manager_ids = selectedIds.value
        }
        if (route.query.search) {
            params.search = route.query.search
        }
        params.period = route.query.period || DEFAULT_PERIOD
        if (params.period === 'custom') {
            if (route.query.date_from) params.date_from = route.query.date_from
            if (route.query.date_to) params.date_to = route.query.date_to
        }
        if (route.query.sort) params.sort = route.query.sort
        if (route.query.dir) params.dir = route.query.dir
        archiveStore.fetchArchive(params)
    },
    { immediate: true },
)
</script>
