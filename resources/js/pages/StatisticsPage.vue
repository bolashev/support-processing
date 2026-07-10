<template>
    <div class="page-root">
        <PortalHeader />

        <div class="content-area">
            <div class="nav-card-wrap">
                <NavTabs />
            </div>

            <div class="section-block statistics-page">
                <div class="section-title-row">
                    <span class="section-title">Статистика</span>
                </div>
                <div class="sub-tabs">
                    <router-link to="/statistics/sales" class="sub-tab" active-class="active">Продающие менеджеры</router-link>
                    <router-link to="/statistics/support" class="sub-tab" active-class="active">Менеджеры поддержки</router-link>
                </div>
                <router-view />
            </div>
        </div>
    </div>
</template>

<script setup>
import PortalHeader from '../components/layout/PortalHeader.vue'
import NavTabs from '../components/layout/NavTabs.vue'
</script>
