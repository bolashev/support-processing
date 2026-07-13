<template>
    <div class="modal-panel modal-panel--docs">
        <div class="modal-panel-header">
            <div class="modal-panel-header-inner">
                <span class="modal-panel-title">Документы</span>
            </div>
        </div>

        <div class="modal-panel-body">
            <div class="modal-docs-list">
                <div
                    v-for="doc in documents"
                    :key="doc.id"
                    class="modal-doc-item"
                >
                    <Icon name="document" :size="20" color="#4D79FF" />
                    <div class="modal-doc-info">
                        <span class="modal-doc-name">{{ doc.name }}</span>
                        <span class="modal-doc-size">{{ doc.size }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Icon from '@/components/ui/Icon.vue'

defineProps({
    documents: {
        type: Array,
        default: () => [
            { id: 1, name: 'Расходная-накладная 55499', size: '1.7 МБ' },
            { id: 2, name: 'Счёт № СЧ4-0010422', size: '0.7 МБ' },
        ],
    },
})
</script>
