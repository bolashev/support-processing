<template>
    <div class="stats-table-card">
        <table class="stats-table" :class="{ 'stats-table--support': variant === 'support' }">
            <colgroup>
                <col v-for="col in columns" :key="col.key" :style="col.width ? { width: col.width } : {}" />
            </colgroup>
            <thead>
                <tr>
                    <th v-for="col in columns" :key="col.key">
                        <div class="stats-col-header">
                            <span>{{ col.label }}</span>
                            <Icon
                                v-if="col.sorted"
                                name="sort"
                                :size="16"
                                class="stats-sort-icon"
                                :color="col.sorted ? '#878B99' : '#959595'"
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
import Icon from '../ui/Icon.vue'

defineProps({
    columns: { type: Array, required: true },
    variant: { type: String, default: 'default' },
})
</script>
