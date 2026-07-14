<template>
    <div class="comment-form" :class="$attrs.class">
        <textarea
            ref="textareaRef"
            :value="modelValue"
            class="comment-input"
            :placeholder="placeholder"
            :rows="rows"
            @input="$emit('update:modelValue', $event.target.value)"
        ></textarea>
        <div class="comment-form-bottom">
            <div class="comment-divider" />
            <button
                class="comment-send"
                :disabled="disabled || !modelValue.trim()"
                @click="$emit('submit')"
            >{{ submitLabel }}</button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
    modelValue: { type: String, default: '' },
    placeholder: { type: String, default: 'Написать комментарий...' },
    submitLabel: { type: String, default: 'Отправить' },
    disabled: { type: Boolean, default: false },
    rows: { type: Number, default: 3 },
})

defineEmits(['update:modelValue', 'submit'])

const textareaRef = ref(null)

function focus() {
    textareaRef.value?.focus()
}

defineExpose({ focus })
</script>
