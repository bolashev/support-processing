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
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M3.66602 3.33301L12.9993 12.6663M3.66602 12.6663L12.9993 3.33301" stroke="#222222" stroke-width="1.5"/>
                </svg>
            </div>
            <div v-else class="search-icon" data-svg-wrapper style="position: relative; flex-shrink: 0">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.33464 15.0003C4.65274 15.0003 1.66797 12.0156 1.66797 8.33366C1.66797 4.65176 4.65274 1.66699 8.33464 1.66699C12.0165 1.66699 15.0013 4.65176 15.0013 8.33366C15.0013 9.87475 14.4784 11.2937 13.6003 12.4228L18.0887 16.9111L16.9102 18.0896L12.4216 13.6011C11.2928 14.4781 9.87473 15.0003 8.33464 15.0003ZM13.3359 8.33398C13.3359 11.0954 11.0974 13.334 8.33594 13.334C5.57451 13.334 3.33594 11.0954 3.33594 8.33398C3.33594 5.57256 5.57451 3.33398 8.33594 3.33398C11.0974 3.33398 13.3359 5.57256 13.3359 8.33398Z" fill="#878B99"/>
                </svg>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
    modelValue: { type: String, default: '' },
    placeholder: { type: String, default: 'Поиск по номеру или контрагенту...' },
})

defineEmits(['update:modelValue'])

const focused = ref(false)
</script>
