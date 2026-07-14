<template>
    <div class="modal-panel modal-panel--history">
        <div class="modal-panel-header">
            <div class="modal-panel-header-inner">
                <span class="modal-panel-title">История заказа</span>
            </div>
        </div>

        <div class="modal-panel-body">
            <div class="modal-history-header">
                <span class="modal-history-title">Комментарии</span>
                <span class="modal-history-count">({{ comments.length }})</span>
            </div>

            <CommentForm v-model="newComment" placeholder="Написать комментарий..." @submit="sendComment" />

            <div
                v-for="comment in comments"
                :key="comment.id"
                class="modal-comment"
            >
                <div class="modal-comment-header">
                    <img class="modal-comment-avatar" :src="comment.avatar" alt="" />
                    <span class="modal-comment-author">{{ comment.author }}</span>
                </div>
                <div class="modal-comment-bubble">
                    <span>{{ comment.text }}</span>
                </div>
                <div class="modal-comment-time">
                    <Icon name="clock" :size="16" color="#828282" />
                    <span>{{ comment.time }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import Icon from '@/components/ui/Icon.vue'
import CommentForm from '@/components/ui/CommentForm.vue'

defineProps({
    comments: {
        type: Array,
        default: () => [
            {
                id: 1,
                author: 'Ольга Ситникова',
                avatar: 'https://placehold.co/30x30',
                text: 'Товар собран, ждем машину',
                time: '14 марта 2026 · 10:30',
            },
            {
                id: 2,
                author: 'Сергей Кудряш',
                avatar: 'https://placehold.co/30x30',
                text: 'Клиент подтвердил самовывоз на понедельник',
                time: '12 марта 2026 · 15:50',
            },
        ],
    },
})

const newComment = ref('')

function sendComment() {
    if (!newComment.value.trim()) return
    // TODO: интеграция с API
    newComment.value = ''
}
</script>
