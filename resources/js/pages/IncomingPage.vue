<template>
    <MainLayout>
        <template #filter>
            <IncomingFilter />
        </template>

        <div class="section-block incoming-page">
            <div class="section-title-row">
                <span class="section-title">Входящие заказы</span>
            </div>
            <div v-if="store.anyInitialLoading && store.allEmpty" class="loading-state">Загрузка...</div>
            <div v-else class="columns-wrap" :class="{ 'columns-wrap--loading': store.anyInitialLoading && store.allEmpty }">
                <div class="columns-row">
                    <KanbanColumn
                        title="Новые"
                        :items="store.newOrders"
                        :total="store.newTotal"
                        :loading="store.columns.new.loading"
                        :loading-more="store.columns.new.loadingMore"
                        :has-more="store.newHasMore"
                        empty-text="Пока здесь пусто.<br>Как только появятся новые заказы,<br>они отобразятся в этом списке"
                        @card-click="openOrder"
                        @load-more="loadMore('new')"
                    />
                    <KanbanColumn
                        title="В работе"
                        :items="store.inProgressOrders"
                        :total="store.inProgressTotal"
                        :loading="store.columns.inProgress.loading"
                        :loading-more="store.columns.inProgress.loadingMore"
                        :has-more="store.inProgressHasMore"
                        empty-text="Нет заказов в работе"
                        @card-click="openOrder"
                        @load-more="loadMore('inProgress')"
                    />
                    <KanbanColumn
                        title="Отгруженные"
                        :items="store.shippedOrders"
                        :total="store.shippedTotal"
                        :loading="store.columns.shipped.loading"
                        :loading-more="store.columns.shipped.loadingMore"
                        :has-more="store.shippedHasMore"
                        :sortable="true"
                        :sort-direction="shippedSortDirection"
                        :sort-label="shippedSortLabel"
                        empty-text="Вы еще ничего не отгружали.<br>Все завершенные заказы будут<br>храниться здесь"
                        @card-click="openOrder"
                        @sort="toggleShippedSort"
                        @load-more="loadMore('shipped')"
                    />
                </div>
            </div>
        </div>

        <OrderModal :visible="!!selectedOrderId" :order-id="selectedOrderId" @close="selectedOrderId = null" />
    </MainLayout>
</template>

<script setup>
import { computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useOrdersStore } from '@/stores/orders'
import MainLayout from '@/components/layout/MainLayout.vue'
import IncomingFilter from '@/components/orders/IncomingFilter.vue'
import KanbanColumn from '@/components/orders/KanbanColumn.vue'
import OrderModal from '@/components/orders/OrderModal.vue'

const store = useOrdersStore()
const route = useRoute()
const router = useRouter()

const selectedOrderId = computed({
    get() {
        return route.query.order ? Number(route.query.order) : null
    },
    set(val) {
        const query = { ...route.query }
        if (val) {
            query.order = val
        } else {
            delete query.order
        }
        router.replace({ query })
    },
})

const shippedSortDirection = computed({
    get() {
        return route.query.shipped_sort || 'none'
    },
    set(val) {
        const query = { ...route.query }
        if (val && val !== 'none') {
            query.shipped_sort = val
        } else {
            delete query.shipped_sort
        }
        router.replace({ query })
    },
})

async function openOrder(id) {
    await store.fetchOrder(id)
    selectedOrderId.value = id
}

function toggleShippedSort() {
    const current = shippedSortDirection.value
    if (current === 'none') {
        shippedSortDirection.value = 'desc'
    } else if (current === 'desc') {
        shippedSortDirection.value = 'asc'
    } else {
        shippedSortDirection.value = 'none'
    }
}

function loadMore(statusKey) {
    const state = store.columns[statusKey]
    if (!state) return
    store.fetchColumn(statusKey, state.lastParams, { append: true })
}

const shippedSortLabel = 'По дате'

watch(selectedOrderId, (id) => {
    if (id) {
        store.fetchOrder(id)
    }
}, { immediate: true })
</script>
