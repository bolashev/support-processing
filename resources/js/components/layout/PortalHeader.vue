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
                <div ref="menuRef" class="portal-right portal-user" @click="toggle">
                    <span>{{ user?.name || '' }}</span>
                    <div class="manager-dropdown-chevron" :class="{ 'manager-dropdown-chevron--open': open }">
                        <Icon name="chevron-down" :size="20" color="#282828" />
                    </div>
                    <div v-if="open" class="manager-dropdown-list manager-dropdown-list--right" @click.stop>
                        <div class="manager-dropdown-list-inner portal-user-dropdown-inner">
                            <div class="manager-dropdown-item" @click="logout">
                                <div class="manager-dropdown-item-inner">
                                    <span class="manager-dropdown-item-name">Выйти</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuth } from '@/composables/useAuth'
import { useClickOutside } from '@/composables/useClickOutside'
import { auth as authApi } from '@/client'
import Icon from '@/components/ui/Icon.vue'

import logoSrc from '../../../images/logo.png'

const { user } = useAuth()

const open = ref(false)
const menuRef = ref(null)

function toggle() {
    open.value = !open.value
}

function logout() {
    authApi.logout().then(({ data }) => {
        window.location.href = data.redirect_url
    })
}

useClickOutside(menuRef, () => { open.value = false })
</script>
