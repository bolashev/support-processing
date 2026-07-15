<template>
    <Teleport to="body">
        <div class="toast-container">
            <TransitionGroup name="toast">
                <ToastItem
                    v-for="toast in toasts.items"
                    :key="toast.id"
                    :message="toast.message"
                    :type="toast.type"
                    @close="toasts.remove(toast.id)"
                />
            </TransitionGroup>
        </div>
    </Teleport>
</template>

<script setup>
import { useToastsStore } from '@/stores/toasts'
import ToastItem from './ToastItem.vue'

const toasts = useToastsStore()
</script>
