<template>
    <div class="page-root">
        <PortalHeader />

        <div class="content-area">
            <div class="nav-card-wrap">
                <NavTabs />
            </div>

            <div class="section-block archive-page">
                <div class="section-title-row">
                    <span class="section-title">Архив заявок</span>
                </div>
                <p class="placeholder">Таблица архива с фильтрами</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import PortalHeader from '../components/layout/PortalHeader.vue'
import NavTabs from '../components/layout/NavTabs.vue'
</script>
