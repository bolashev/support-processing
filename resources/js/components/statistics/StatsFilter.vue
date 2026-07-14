<template>
    <div class="stats-filter-card">
        <div class="stats-filter-left">
            <ManagerDropdown
                :items="managers"
                :selected-ids="selectedManagerIds"
                :prefix="managerPrefix"
                @update:selected-ids="$emit('update:selectedManagerIds', $event)"
            />

            <PeriodSwitcher
                :model-value="modelValue"
                :date-range="dateRange"
                @update:model-value="$emit('update:modelValue', $event)"
                @update:date-range="$emit('update:dateRange', $event)"
            />
        </div>

        <div class="stats-export" @click="$emit('export')">
            <Icon name="download" :size="20" color="#282828" />
            <span><span class="stats-export-text">Экспорт таблицы</span><span class="stats-export-ext">.xlsx</span></span>
        </div>
    </div>
</template>

<script setup>
import ManagerDropdown from '@/components/ui/ManagerDropdown.vue'
import PeriodSwitcher from '@/components/ui/PeriodSwitcher.vue'
import Icon from '@/components/ui/Icon.vue'

defineProps({
    managers: { type: Array, default: () => [] },
    selectedManagerIds: { type: Array, default: () => [] },
    managerPrefix: { type: String, default: '' },
    modelValue: { type: String, default: 'today' },
    dateRange: { type: Object, default: null },
})

defineEmits(['update:modelValue', 'update:dateRange', 'export', 'update:selectedManagerIds'])
</script>
