<template>
    <div class="page-root">
        <PortalHeader />

        <div class="content-area">
            <div class="nav-card-wrap">
                <NavTabs />
                <slot name="filter" />
            </div>

            <slot />
        </div>
    </div>
</template>

<script setup>
import PortalHeader from './PortalHeader.vue'
import NavTabs from './NavTabs.vue'
</script>
