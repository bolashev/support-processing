<template>
    <div class="page-root">
        <PortalHeader />

        <div class="content-area">
            <div class="nav-card-wrap">
                <NavTabs />
            </div>

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
                                    <svg class="stats-metric-arrow" width="14" height="35" viewBox="0 0 14 35" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.10764 15.5416C2.25087 15.6852 2.48322 15.6852 2.62599 15.5416L6.18371 11.9768L6.18371 23.6326C6.18371 23.8355 6.34766 24 6.5503 24C6.75248 24 6.9169 23.8355 6.9169 23.6326L6.9169 11.9768L10.4746 15.5414C10.6174 15.6849 10.8497 15.6849 10.993 15.5414C11.1362 15.3981 11.1362 15.1655 10.993 15.022L7.2805 11.3025C7.08592 11.1073 6.82663 11 6.5503 11C6.27375 11 6.01469 11.1075 5.81965 11.3027L2.10764 15.0222C1.96464 15.1657 1.96464 15.3983 2.10764 15.5416Z" fill="#3AB88D" stroke="#3AB88D" stroke-width="0.5"/>
                                    </svg>
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
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M11 15H13C13.5304 15 14.0391 14.7893 14.4142 14.4142C14.7893 14.0391 15 13.5304 15 13C15 12.4696 14.7893 11.9609 14.4142 11.5858C14.0391 11.2107 13.5304 11 13 11H10C9.4 11 8.9 11.2 8.6 11.6L3 17M7 21L8.6 19.6C8.9 19.2 9.4 19 10 19H14C15.1 19 16.1 18.6 16.8 17.8L21.4 13.4C21.7859 13.0353 22.0111 12.5323 22.0261 12.0016C22.0411 11.4708 21.8447 10.9559 21.48 10.57C21.1153 10.1841 20.6123 9.95889 20.0816 9.94389C19.5508 9.92888 19.0359 10.1253 18.65 10.49L14.45 14.39M2 16L8 22M18.9 9C18.9 10.6016 17.6016 11.9 16 11.9C14.3984 11.9 13.1 10.6016 13.1 9C13.1 7.39837 14.3984 6.1 16 6.1C17.6016 6.1 18.9 7.39837 18.9 9ZM9 5C9 6.65685 7.65685 8 6 8C4.34315 8 3 6.65685 3 5C3 3.34315 4.34315 2 6 2C7.65685 2 9 3.34315 9 5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>Продающие менеджеры</span>
                            </router-link>
                            <router-link to="/statistics/support" class="stats-sub-tab" active-class="active">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M2.5 9.1665H5C5.44203 9.1665 5.86595 9.3421 6.17851 9.65466C6.49107 9.96722 6.66667 10.3911 6.66667 10.8332V13.3332C6.66667 13.7752 6.49107 14.1991 6.17851 14.5117C5.86595 14.8242 5.44203 14.9998 5 14.9998H4.16667C3.72464 14.9998 3.30072 14.8242 2.98816 14.5117C2.67559 14.1991 2.5 13.7752 2.5 13.3332V9.1665ZM2.5 9.1665C2.5 8.18159 2.69399 7.20632 3.0709 6.29638C3.44781 5.38644 4.00026 4.55964 4.6967 3.8632C5.39314 3.16676 6.21993 2.61432 7.12987 2.23741C8.03982 1.8605 9.01509 1.6665 10 1.6665C10.9849 1.6665 11.9602 1.8605 12.8701 2.23741C13.7801 2.61432 14.6069 3.16676 15.3033 3.8632C15.9997 4.55964 16.5522 5.38644 16.9291 6.29638C17.306 7.20632 17.5 8.18159 17.5 9.1665M17.5 9.1665V13.3332M17.5 9.1665H15C14.558 9.1665 14.134 9.3421 13.8215 9.65466C13.5089 9.96722 13.3333 10.3911 13.3333 10.8332V13.3332C13.3333 13.7752 13.5089 14.1991 13.8215 14.5117C14.134 14.8242 14.558 14.9998 15 14.9998H15.8333C16.2754 14.9998 16.6993 14.8242 17.0118 14.5117C17.3244 14.1991 17.5 13.7752 17.5 13.3332M17.5 13.3332V14.9998C17.5 15.8839 17.1488 16.7317 16.5237 17.3569C15.8986 17.982 15.0507 18.3332 14.1667 18.3332H10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>Менеджеры поддержки</span>
                            </router-link>
                        </div>
                    </div>

                    <router-view />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import PortalHeader from '../components/layout/PortalHeader.vue'
import NavTabs from '../components/layout/NavTabs.vue'

const route = useRoute()

const isSales = computed(() => route.name === 'stats-sales')

const today = computed(() =>
    new Date().toLocaleDateString('ru-RU', { day: 'numeric', month: 'long' }),
)
</script>
