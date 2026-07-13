<template>
    <div ref="containerRef" class="manager-dropdown" @click="toggle">
        <span v-if="prefix" class="manager-dropdown-prefix">{{ prefix }}</span>
        <span class="manager-dropdown-label">{{ displayLabel }}</span>
        <span class="manager-dropdown-sep" v-if="showSep">·</span>
        <span class="manager-dropdown-count" v-if="showCount">{{ displayCount }}</span>
        <div class="manager-dropdown-chevron" :class="{ 'manager-dropdown-chevron--open': open }">
            <Icon name="chevron-down" :size="20" color="#282828" />
        </div>

        <div v-if="open" class="manager-dropdown-list" @click.stop>
            <div class="manager-dropdown-list-inner">
                <div class="manager-dropdown-list-scroll">
                    <div
                        v-for="item in items"
                        :key="item.id"
                        class="manager-dropdown-item"
                        @click.stop="toggleItem(item.id)"
                    >
                        <div class="manager-dropdown-item-inner">
                            <span class="manager-dropdown-item-name">{{ item.name }}</span>
                            <svg v-if="isSelected(item.id)" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <rect x="0.5" y="0.5" width="19" height="19" rx="3.5" stroke="#4699F1"/>
                                <path d="M4.5 10.5L8 14L15.5 6.5" stroke="#4699F1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <svg v-else width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <rect x="0.75" y="0.75" width="18.5" height="18.5" rx="3.25" fill="white"/>
                                <rect x="0.75" y="0.75" width="18.5" height="18.5" rx="3.25" stroke="#DFE1EB" stroke-width="1.5"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import Icon from './Icon.vue'

const props = defineProps({
    items: { type: Array, default: () => [] },
    selectedIds: { type: Array, default: () => [] },
    prefix: { type: String, default: '' },
    label: { type: String, default: '' },
    showSep: { type: Boolean, default: true },
    showCount: { type: Boolean, default: true },
})

const emit = defineEmits(['update:selectedIds'])

const open = ref(false)
const containerRef = ref(null)

const isAllSelected = computed(() => props.selectedIds.length === props.items.length)
const isNoneSelected = computed(() => props.selectedIds.length === 0)

const displayLabel = computed(() => {
    if (props.label) return props.label
    if (isNoneSelected.value || isAllSelected.value) return 'Все менеджеры'
    return 'Выбрано'
})

const displayCount = computed(() => {
    if (isNoneSelected.value || isAllSelected.value) return props.items.length
    return props.selectedIds.length
})

function isSelected(id) {
    if (props.selectedIds.length === 0) return true
    return props.selectedIds.includes(id)
}

function toggleItem(id) {
    let next
    if (props.selectedIds.length === 0) {
        next = props.items.filter(i => i.id !== id).map(i => i.id)
    } else if (isSelected(id)) {
        next = props.selectedIds.filter(i => i !== id)
    } else {
        next = [...props.selectedIds, id]
    }
    if (next.length === props.items.length) next = []
    emit('update:selectedIds', next)
}

function toggle() {
    open.value = !open.value
}

function onClickOutside(e) {
    if (open.value && containerRef.value && !containerRef.value.contains(e.target)) {
        open.value = false
    }
}

onMounted(() => document.addEventListener('click', onClickOutside))
onBeforeUnmount(() => document.removeEventListener('click', onClickOutside))
</script>
