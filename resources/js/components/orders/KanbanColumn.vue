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
                    <Icon name="sort" :size="16" color="#959595" />
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
import Icon from '../ui/Icon.vue'

defineProps({
    title: { type: String, required: true },
    emptyText: { type: String, default: '' },
    cards: { type: Array, default: () => [] },
    sortable: { type: Boolean, default: false },
    sortLabel: { type: String, default: 'По дате' },
})

defineEmits(['cardClick', 'sort'])
</script>
