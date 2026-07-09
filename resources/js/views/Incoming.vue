<template>
    <div>
        <h2>Входящие заявки</h2>
        <div class="sub-tabs">
            <router-link to="/incoming/new">Новые</router-link>
            <router-link to="/incoming/in-progress">В работе</router-link>
            <router-link to="/incoming/shipped">Отгруженные</router-link>
        </div>
        <router-view />
    </div>
</template>

<style scoped>
.sub-tabs { display: flex; gap: 12px; margin-bottom: 16px; }
.sub-tabs a { padding: 6px 14px; text-decoration: none; color: #555; border-radius: 6px; background: #fff; border: 1px solid #e0e0e0; font-size: 13px; }
.sub-tabs a.router-link-exact-active { color: #1a73e8; border-color: #1a73e8; background: #e8f0fe; }
</style>
