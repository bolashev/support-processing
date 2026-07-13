<template>
    <div class="search-box" :class="{ 'search-box--focused': focused, 'search-box--filled': modelValue }">
        <div class="search-inner">
            <input
                class="search-input"
                :placeholder="placeholder"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                @focus="focused = true"
                @blur="focused = false"
            />
            <div v-if="modelValue" class="search-clear" @mousedown.prevent="$emit('update:modelValue', '')">
                <Icon name="close" :size="16" color="#222222" />
            </div>
            <div v-else class="search-icon">
                <Icon name="search" :size="20" color="#878B99" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import Icon from './Icon.vue'

defineProps({
    modelValue: { type: String, default: '' },
    placeholder: { type: String, default: 'Поиск по номеру или контрагенту...' },
})

defineEmits(['update:modelValue'])

const focused = ref(false)
</script>
