<template>
    <div class="page-root">
        <PortalHeader />

        <div class="content-area">
            <div class="nav-card-wrap">
                <NavTabs />
            </div>

            <div class="section-block">
                <div class="section-title-row">
                    <span class="section-title">Заказ #{{ $route.params.id }}</span>
                </div>
                <p class="placeholder">Детали заказа</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import PortalHeader from '../components/layout/PortalHeader.vue'
import NavTabs from '../components/layout/NavTabs.vue'
</script>
