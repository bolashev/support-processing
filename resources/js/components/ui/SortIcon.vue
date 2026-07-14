<template>
    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <template v-if="direction === 'asc'">
            <rect x="2" y="3" width="6" height="2" :fill="color" />
            <rect x="2" y="7" width="9" height="2" :fill="color" />
            <rect x="2" y="11" width="12" height="2" :fill="color" />
        </template>
        <template v-else>
            <rect x="2" y="3" width="12" height="2" :fill="color" />
            <rect x="2" y="7" width="9" height="2" :fill="color" />
            <rect x="2" y="11" width="6" height="2" :fill="color" />
        </template>
    </svg>
</template>

<script setup>
defineProps({
    direction: { type: String, default: 'none' },
    color: { type: String, default: '#959595' },
})
</script>
