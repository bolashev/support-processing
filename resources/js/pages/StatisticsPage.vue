<template>
    <MainLayout>
        <div class="section-block statistics-page">
            <template v-if="isSales">
                <div class="stats-summary-title">Общая сводка на сегодня, {{ today }}</div>

                <div class="stats-cards-row">
                    <div class="stats-card stats-card--count">
                        <div class="stats-metric">
                            <div class="stats-metric-value stats-metric-value--strong">12</div>
                            <div class="stats-metric-label">Менеджеров<br />в анализе</div>
                        </div>
                    </div>
                    <div class="stats-card stats-card--summary">
                        <div class="stats-metric">
                            <div class="stats-metric-value-row">
                                <span class="stats-metric-value stats-metric-value--success">1,450</span>
                                <Icon name="arrow-up" viewBox="0 0 14 35" class="stats-metric-arrow" color="#3AB88D" />
                            </div>
                            <div class="stats-metric-label">Всего отгружено</div>
                        </div>
                        <div class="stats-metric">
                            <div class="stats-metric-value">725</div>
                            <div class="stats-metric-label">Через процессинг</div>
                        </div>
                        <div class="stats-metric">
                            <div class="stats-metric-value-row">
                                <span class="stats-metric-value">50</span><span class="stats-metric-percent">%</span>
                            </div>
                            <div class="stats-metric-label">% через процессинг</div>
                        </div>
                        <div class="stats-metric">
                            <div class="stats-metric-value stats-metric-value--strong">24</div>
                            <div class="stats-metric-label">Среднее<br />в раб. день</div>
                        </div>
                    </div>
                </div>
            </template>

            <div class="stats-tabs-section">
                <div class="stats-tabs-card">
                    <div class="stats-tabs-header">
                        <router-link to="/statistics/sales" class="stats-sub-tab" active-class="active">
                            <Icon name="user" :size="24" />
                            <span>Продающие менеджеры</span>
                        </router-link>
                        <router-link to="/statistics/support" class="stats-sub-tab" active-class="active">
                            <Icon name="chat" :size="20" />
                            <span>Менеджеры поддержки</span>
                        </router-link>
                    </div>
                </div>

                <router-view />
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import MainLayout from '../components/layout/MainLayout.vue'
import Icon from '../components/ui/Icon.vue'

const route = useRoute()

const isSales = computed(() => route.name === 'stats-sales')

const today = computed(() =>
    new Date().toLocaleDateString('ru-RU', { day: 'numeric', month: 'long' }),
)
</script>
