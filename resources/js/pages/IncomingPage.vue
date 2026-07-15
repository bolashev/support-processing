<template>
    <MainLayout>
        <template #filter>
            <IncomingFilter />
        </template>

        <div class="section-block">
            <div class="section-title-row">
                <span class="section-title">Входящие заявки</span>
            </div>
            <div v-if="store.loading && !store.allOrders.length" class="loading-state">Загрузка...</div>
            <div v-else class="columns-wrap" :class="{ 'columns-wrap--loading': store.loading }">
                <div class="columns-row">
                    <KanbanColumn
                        title="Новые"
                        :cards="store.newOrders"
                        empty-text="Пока здесь пусто.<br>Как только появятся новые заявки,<br>они отобразятся в этом списке"
                        @card-click="openOrder"
                    />
                    <KanbanColumn
                        title="В работе"
                        :cards="store.inProgressOrders"
                        empty-text="Нет заявок в работе"
                        @card-click="openOrder"
                    />
                    <KanbanColumn
                        title="Отгруженные"
                        :cards="store.shippedOrders"
                        :sortable="true"
                        :sort-direction="shippedSortDirection"
                        :sort-label="shippedSortLabel"
                        empty-text="Вы еще ничего не отгружали.<br>Все завершенные заказы будут<br>храниться здесь"
                        @card-click="openOrder"
                        @sort="toggleShippedSort"
                    />
                </div>
            </div>
        </div>

        <OrderModal :visible="!!selectedOrderId" :order-id="selectedOrderId" @close="selectedOrderId = null" />
    </MainLayout>
</template>

<script setup>
import { computed } from 'vue'
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

function openOrder(id) {
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

const shippedSortLabel = 'По дате'
</script>

<style scoped>
.columns-wrap--loading {
    opacity: 0.5;
    pointer-events: none;
    transition: opacity 0.2s ease;
}
</style>
