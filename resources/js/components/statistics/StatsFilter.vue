<template>
    <div class="stats-filter-card">
        <div class="stats-filter-left">
            <ManagerDropdown
                :items="managers"
                :selected-ids="selectedManagerIds"
                :prefix="managerPrefix"
                @update:selected-ids="$emit('update:selectedManagerIds', $event)"
            />

            <PeriodSwitcher :model-value="modelValue" @update:model-value="$emit('update:modelValue', $event)" />
        </div>

        <div class="stats-export" @click="$emit('export')">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M10.0003 14.1667V2.5M15.0003 9.16667L10.0003 14.1667L5.00033 9.16667M15.8337 17.5H4.16699" stroke="#282828" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span><span class="stats-export-text">Экспорт таблицы</span><span class="stats-export-ext">.xlsx</span></span>
        </div>
    </div>
</template>

<script setup>
import ManagerDropdown from '../ui/ManagerDropdown.vue'
import PeriodSwitcher from '../ui/PeriodSwitcher.vue'

defineProps({
    managers: { type: Array, default: () => [] },
    selectedManagerIds: { type: Array, default: () => [] },
    managerPrefix: { type: String, default: '' },
    modelValue: { type: String, default: 'today' },
})

defineEmits(['update:modelValue', 'export', 'update:selectedManagerIds'])
</script>
