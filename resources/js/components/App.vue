<template>
    <router-view />
    <ToastContainer />
</template>

<script setup>
import { watch } from 'vue'
import { useRouter } from 'vue-router'
import ToastContainer from '@/components/ui/ToastContainer.vue'

const router = useRouter()

watch(
    () => router.currentRoute.value,
    (route) => {
        if (route.meta?.title) {
            document.title = route.meta.title
        }
    },
    { immediate: true },
)
</script>
