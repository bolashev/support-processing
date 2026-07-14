<template>
    <div class="stats-table-card">
        <table class="stats-table" :class="{ 'stats-table--support': variant === 'support' }">
            <colgroup>
                <col v-for="col in columns" :key="col.key" :style="col.width ? { width: col.width } : {}" />
            </colgroup>
            <thead>
                <tr>
                    <th v-for="col in columns" :key="col.key">
                        <div
                            class="stats-col-header"
                            :class="{ 'stats-col-header--sortable': col.sortable }"
                            @click="col.sortable && $emit('sort', col.key)"
                        >
                            <span>{{ col.label }}</span>
                            <SortIcon
                                v-if="col.sortable && sortKey === col.key"
                                :direction="sortDirection"
                                color="#878B99"
                            />
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <slot />
            </tbody>
        </table>
    </div>
</template>

<script setup>
import SortIcon from './SortIcon.vue'

defineProps({
    columns: { type: Array, required: true },
    variant: { type: String, default: 'default' },
    sortKey: { type: String, default: null },
    sortDirection: { type: String, default: 'none' },
})

defineEmits(['sort'])
</script>
