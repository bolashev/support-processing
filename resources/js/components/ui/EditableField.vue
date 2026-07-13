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
                <Icon name="edit" :size="16" color="#959595" />
            </slot>
        </div>

        <div v-if="isEditing" class="editable-field__actions">
            <button class="editable-field__action editable-field__action--cancel" @click="cancel" title="Отменить">
                <Icon name="cancel" :size="20" color="#959595" />
            </button>
            <button class="editable-field__action editable-field__action--confirm" @click="confirm" title="Сохранить">
                <Icon name="confirm" :size="20" color="#4699F1" />
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, nextTick } from 'vue'
import Icon from './Icon.vue'

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
