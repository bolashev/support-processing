<template>
    <div class="app-layout">
        <nav class="sidebar">
            <router-link
                v-for="tab in tabs"
                :key="tab.path"
                :to="tab.path"
                class="tab"
                :class="{ active: $route.path.startsWith(tab.path) }"
            >
                <span class="tab-icon">{{ tab.icon }}</span>
                <span class="tab-label">{{ tab.label }}</span>
            </router-link>
        </nav>
        <main class="content">
            <div class="user-info" v-if="user">
                {{ user.name }} &lt;{{ user.email }}&gt;
            </div>
            <router-view />
        </main>
    </div>
</template>

<script setup>
const user = window.__INITIAL_USER__ || null;

const tabs = [
    { path: '/incoming', label: 'Входящие заявки', icon: '↑' },
    { path: '/archive', label: 'Архив', icon: '📥' },
    { path: '/statistics', label: 'Статистика', icon: '📊' },
    { path: '/notes', label: 'Заметки', icon: '✏' },
];
</script>

<style>
body {
    margin: 0;
    font-family: system-ui, sans-serif;
    background: #f5f5f5;
    color: #222;
}
.app-layout {
    display: flex;
    min-height: 100vh;
}
.sidebar {
    width: 220px;
    background: #fff;
    border-right: 1px solid #e0e0e0;
    padding: 16px 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.tab {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 20px;
    text-decoration: none;
    color: #555;
    font-size: 14px;
    border-left: 3px solid transparent;
    transition: 0.15s;
}
.tab:hover {
    background: #f0f0f0;
}
.tab.active {
    color: #1a73e8;
    border-left-color: #1a73e8;
    background: #e8f0fe;
}
.tab-icon {
    font-size: 18px;
    width: 24px;
    text-align: center;
}
.content {
    flex: 1;
    padding: 24px;
}
.user-info {
    font-size: 12px;
    color: #888;
    margin-bottom: 16px;
    padding: 6px 12px;
    background: #fff;
    border-radius: 6px;
    border: 1px solid #e0e0e0;
}
.placeholder {
    color: #999;
    font-size: 14px;
    padding: 24px;
    background: #fff;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}
</style>
