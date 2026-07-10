<template>
    <div class="kanban-col" :class="{ 'kanban-col--has-cards': cards.length > 0 }">
        <div class="col-top">
            <div class="col-header-row">
                <div class="col-header-left">
                    <span class="col-name">{{ title }}</span>
                    <span class="col-sep">·</span>
                    <span class="col-count">{{ cards.length }}</span>
                </div>
                <div v-if="sortable" class="col-sort" @click="$emit('sort')">
                    <span class="col-sort-label">{{ sortLabel }}</span>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <rect x="14" y="5" width="6" height="2" transform="rotate(180 14 5)" fill="#959595"/>
                        <rect x="14" y="9" width="9" height="2" transform="rotate(180 14 9)" fill="#959595"/>
                        <rect x="14" y="13" width="12" height="2" transform="rotate(180 14 13)" fill="#959595"/>
                    </svg>
                </div>
            </div>
            <div class="col-line" />
        </div>
        <div class="col-body" :class="{ 'col-body--cards': cards.length > 0 }">
            <template v-if="cards.length > 0">
                <OrderCard
                    v-for="card in cards"
                    :key="card.id"
                    :order="card"
                    @click="$emit('cardClick', card.id)"
                />
            </template>
            <template v-else>
                <span class="col-empty" v-html="emptyText" />
            </template>
        </div>
    </div>
</template>

<script setup>
import OrderCard from './OrderCard.vue'

defineProps({
    title: { type: String, required: true },
    emptyText: { type: String, default: '' },
    cards: { type: Array, default: () => [] },
    sortable: { type: Boolean, default: false },
    sortLabel: { type: String, default: 'По дате' },
})

defineEmits(['cardClick', 'sort'])
</script>
