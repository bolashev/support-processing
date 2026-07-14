<template>
    <div class="nav-card-filter">
        <div class="filter-row">
            <ManagerDropdown
                :items="managers"
                v-model:selected-ids="selectedIds"
                prefix="Менеджер заявки:"
                :label="selectedLabel"
                :show-sep="showCounter"
                :show-count="showCounter"
            />
        </div>
        <SearchInput v-model="searchValue" />
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import SearchInput from '@/components/ui/SearchInput.vue'
import ManagerDropdown from '@/components/ui/ManagerDropdown.vue'

const selectedIds = ref([1])
const searchValue = ref('')

const managers = [
    { id: 1, name: 'Вы' },
    { id: 2, name: 'Константинов Константин Конст...' },
    { id: 3, name: 'Зайцева Екатерина Сергеевна' },
    { id: 4, name: 'Пронченко Зинаида Сергеевна' },
    { id: 5, name: 'Дарья Сергеевна' },
]

const selectedLabel = computed(() => {
    if (selectedIds.value.length === 1 && selectedIds.value[0] === 1) return 'Только я'
    return ''
})

const showCounter = computed(() => {
    return selectedIds.value.length > 1 && selectedIds.value.length < managers.length
})
</script>
