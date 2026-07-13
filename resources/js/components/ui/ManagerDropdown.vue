<template>
    <div ref="containerRef" class="manager-dropdown" @click="toggle">
        <span v-if="prefix" class="manager-dropdown-prefix">{{ prefix }}</span>
        <span class="manager-dropdown-label">{{ displayLabel }}</span>
        <span class="manager-dropdown-sep" v-if="showSep">·</span>
        <span class="manager-dropdown-count" v-if="showCount">{{ displayCount }}</span>
        <div data-svg-wrapper class="manager-dropdown-chevron" :class="{ 'manager-dropdown-chevron--open': open }">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <mask id="mask-manager-chevron" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="20" height="20">
                    <rect x="20" width="20" height="20" transform="rotate(90 20 0)" fill="#D9D9D9"/>
                </mask>
                <g mask="url(#mask-manager-chevron)">
                    <path d="M9.99967 10.875L13.4163 7.45835C13.5691 7.30558 13.7462 7.22919 13.9476 7.22919C14.149 7.22919 14.3261 7.30558 14.4788 7.45835C14.6316 7.61113 14.708 7.78821 14.708 7.9896C14.708 8.19099 14.6293 8.37042 14.4718 8.5279L10.5205 12.4792C10.4455 12.5486 10.3643 12.6007 10.2768 12.6354C10.1893 12.6702 10.0955 12.6875 9.99551 12.6875C9.89551 12.6875 9.80176 12.6702 9.71426 12.6354C9.62676 12.6007 9.54829 12.5486 9.47884 12.4792L5.52755 8.5279C5.37008 8.37042 5.29481 8.19446 5.30176 8.00002C5.3087 7.80558 5.38856 7.63196 5.54134 7.47919C5.69412 7.32641 5.8712 7.25002 6.07259 7.25002C6.27398 7.25002 6.45106 7.32641 6.60384 7.47919L9.99967 10.875Z" fill="#282828"/>
                </g>
            </svg>
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
                                <path d="M4.5 10.5L8 14L15.5 6.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
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
