<template>
    <div class="editable-field" :class="{ 'editable-field--editing': isEditing, 'editable-field--white': whiteBg }">
        <div class="editable-field__content">
            <span class="editable-field__label">{{ label }}</span>
            <template v-if="!isEditing">
                <span class="editable-field__value" :class="{ 'editable-field__value--link': link }">{{ modelValue }}</span>
            </template>
            <template v-else>
                <input
                    ref="inputRef"
                    v-model="editValue"
                    class="editable-field__input"
                    :type="inputType"
                    @keydown.enter="confirm"
                    @keydown.escape="cancel"
                />
            </template>
        </div>

        <div v-if="!isEditing" class="editable-field__icon" @click="startEdit">
            <slot name="icon">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M8.66679 14.0001H14.0001M14.1161 4.54138C14.4686 4.189 14.6666 3.71103 14.6667 3.21262C14.6668 2.71421 14.4688 2.23619 14.1165 1.88372C13.7641 1.53124 13.2861 1.33319 12.7877 1.33313C12.2893 1.33307 11.8113 1.531 11.4588 1.88338L2.56145 10.7827C2.40667 10.9371 2.29219 11.1271 2.22812 11.3361L1.34745 14.2374C1.33022 14.295 1.32892 14.3563 1.34369 14.4146C1.35845 14.473 1.38873 14.5262 1.43132 14.5687C1.4739 14.6112 1.5272 14.6414 1.58556 14.6561C1.64392 14.6708 1.70516 14.6694 1.76279 14.6521L4.66479 13.7721C4.87357 13.7086 5.06357 13.5948 5.21812 13.4407L14.1161 4.54138Z" stroke="#959595" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </slot>
        </div>

        <div v-if="isEditing" class="editable-field__actions">
            <button class="editable-field__action editable-field__action--cancel" @click="cancel" title="Отменить">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M12.5001 7.49996L7.50008 12.5M7.50008 7.49996L12.5001 12.5M18.3334 9.99996C18.3334 14.6023 14.6025 18.3333 10.0001 18.3333C5.39771 18.3333 1.66675 14.6023 1.66675 9.99996C1.66675 5.39759 5.39771 1.66663 10.0001 1.66663C14.6025 1.66663 18.3334 5.39759 18.3334 9.99996Z" stroke="#959595" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <button class="editable-field__action editable-field__action--confirm" @click="confirm" title="Сохранить">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M7.50008 9.99996L9.16675 11.6666L12.5001 8.33329M18.3334 9.99996C18.3334 14.6023 14.6025 18.3333 10.0001 18.3333C5.39771 18.3333 1.66675 14.6023 1.66675 9.99996C1.66675 5.39759 5.39771 1.66663 10.0001 1.66663C14.6025 1.66663 18.3334 5.39759 18.3334 9.99996Z" stroke="#4699F1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, nextTick, watch } from 'vue'

const props = defineProps({
    modelValue: { type: String, default: '' },
    label: { type: String, default: '' },
    link: { type: Boolean, default: false },
    whiteBg: { type: Boolean, default: false },
    inputType: { type: String, default: 'text' },
})

const emit = defineEmits(['update:modelValue', 'save'])

const isEditing = ref(false)
const editValue = ref('')
const inputRef = ref(null)

const startEdit = async () => {
    editValue.value = props.modelValue
    isEditing.value = true
    await nextTick()
    inputRef.value?.focus()
    const len = inputRef.value?.value?.length || 0
    inputRef.value?.setSelectionRange(len, len)
}

const confirm = () => {
    isEditing.value = false
    emit('update:modelValue', editValue.value)
    emit('save', editValue.value)
}

const cancel = () => {
    isEditing.value = false
    editValue.value = props.modelValue
}
</script>
