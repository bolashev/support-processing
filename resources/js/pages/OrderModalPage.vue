<template>
    <MainLayout>
        <div class="section-block">
            <div class="section-title-row">
                <span class="section-title">Заказ #{{ $route.params.id }}</span>
            </div>
            <p class="placeholder">Детали заказа</p>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from '../components/layout/MainLayout.vue'
</script>
