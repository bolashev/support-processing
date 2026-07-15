<template>
    <div :class="['toast-item', `toast-item--${type}`]">
        <div class="toast-content">
            <span class="toast-message">{{ message }}</span>
        </div>
        <button class="toast-close" @click.stop="$emit('close')">
            <Icon name="close" :size="14" />
        </button>
    </div>
</template>

<script setup>
import Icon from './Icon.vue'

defineProps({
    message: { type: String, required: true },
    type: { type: String, default: 'error' },
})

defineEmits(['close'])
</script>
