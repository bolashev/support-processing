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
                    <img class="modal-comment-avatar" :src="comment.user?.avatar_url || 'https://placehold.co/30x30'" alt="" />
                    <span class="modal-comment-author">{{ comment.user?.name || 'Аноним' }}</span>
                </div>
                <div class="modal-comment-bubble">
                    <span>{{ comment.body }}</span>
                </div>
                <div class="modal-comment-time">
                    <Icon name="clock" :size="16" color="#828282" />
                    <span>{{ formatTime(comment.created_at) }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useOrdersStore } from '@/stores/orders'
import Icon from '@/components/ui/Icon.vue'
import CommentForm from '@/components/ui/CommentForm.vue'

const props = defineProps({
    orderId: { type: Number, required: true },
    comments: { type: Array, default: () => [] },
})

const store = useOrdersStore()
const newComment = ref('')

async function sendComment() {
    const text = newComment.value.trim()
    if (!text) return
    await store.addComment(props.orderId, text)
    newComment.value = ''
}

function formatTime(dateStr) {
    if (!dateStr) return ''
    const d = new Date(dateStr)
    return d.toLocaleDateString('ru-RU', { day: 'numeric', month: 'long', year: 'numeric' }) +
        ' · ' + d.toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' })
}
</script>
