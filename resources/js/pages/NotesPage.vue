<template>
    <div class="page-root">
        <PortalHeader />

        <div class="content-area">
            <div class="nav-card-wrap">
                <NavTabs />
            </div>

            <div class="section-block notes-page">
                <div class="section-title-row">
                    <span class="section-title">Заметки</span>
                </div>
                <p class="placeholder">Личные заметки</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import PortalHeader from '../components/layout/PortalHeader.vue'
import NavTabs from '../components/layout/NavTabs.vue'
</script>
