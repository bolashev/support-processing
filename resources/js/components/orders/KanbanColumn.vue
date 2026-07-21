<template>
    <div class="kanban-col" :class="{ 'kanban-col--has-cards': total > 0 }">
        <div class="col-top">
            <div class="col-header-row">
                <div class="col-header-left">
                    <span class="col-name">{{ title }}</span>
                    <span class="col-sep">·</span>
                    <span class="col-count">{{ total }}</span>
                </div>
                <div v-if="sortable" class="col-sort" @click="$emit('sort')">
                    <span class="col-sort-label">{{ sortLabel }}</span>
                    <SortIcon v-if="sortDirection !== 'none'" :direction="sortDirection" color="#878B99" />
                </div>
            </div>
            <div class="col-line" />
        </div>
        <div ref="bodyEl" class="col-body" :class="{ 'col-body--cards': items.length > 0 }">
            <div v-if="loading && items.length === 0" class="col-loading">Загрузка…</div>
            <template v-else-if="items.length > 0">
                <OrderCard
                    v-for="card in items"
                    :key="card.id"
                    :order="card"
                    @click="$emit('cardClick', card.id)"
                />
                <div ref="sentinelEl" class="col-sentinel">
                    <span v-if="loadingMore">Загрузка…</span>
                    <span v-else-if="!hasMore" class="col-sentinel-end">Все заказы загружены</span>
                </div>
            </template>
            <span v-else class="col-empty" v-html="emptyText" />
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import OrderCard from './OrderCard.vue'
import SortIcon from '@/components/ui/SortIcon.vue'
import { useInfiniteScroll } from '@/composables/useInfiniteScroll'

const props = defineProps({
    title: { type: String, required: true },
    emptyText: { type: String, default: '' },
    items: { type: Array, default: () => [] },
    total: { type: Number, default: 0 },
    loading: { type: Boolean, default: false },
    loadingMore: { type: Boolean, default: false },
    hasMore: { type: Boolean, default: false },
    sortable: { type: Boolean, default: false },
    sortLabel: { type: String, default: 'По дате' },
    sortDirection: { type: String, default: 'none' },
})

const emit = defineEmits(['cardClick', 'sort', 'loadMore'])

const bodyEl = ref(null)
const sentinelEl = ref(null)

function tryLoadMore() {
    if (props.loading || props.loadingMore || !props.hasMore) return
    emit('loadMore')
}

useInfiniteScroll(bodyEl, sentinelEl, tryLoadMore, { rootMargin: '100px' })

watch(() => props.items.length, (newLen, oldLen) => {
    if (newLen < oldLen && bodyEl.value) {
        bodyEl.value.scrollTop = 0
    }
})
</script>
