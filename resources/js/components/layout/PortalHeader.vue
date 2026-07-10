<template>
    <div class="portal-header">
        <div class="portal-header-card">
            <div class="portal-header-width">
                <a class="portal-left" href="https://portal.trapeza.ru">
                    <div class="portal-pill">
                        <svg width="19" height="19" viewBox="0 0 19 19" fill="none">
                            <path d="M11.875 4.75L7.125 9.5L11.875 14.25" stroke="#878B99" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span class="portal-link">Портал</span>
                </a>
                <img class="portal-logo" :src="logoSrc" alt="" />
                <div class="portal-right">{{ user?.name || '' }}</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useAuth } from '../../composables/useAuth'

const { user } = useAuth()
const logoSrc = '/images/logo.png'
</script>
