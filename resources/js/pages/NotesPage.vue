<template>
    <MainLayout>
        <div class="section-block notes-page">
            <div class="section-title-row">
                <span class="section-title">Личные заметки</span>
            </div>

            <div v-if="notesStore.loading" class="loading-state">Загрузка...</div>
            <div v-else class="notes-columns">
                <div class="notes-panel">
                    <div class="notes-panel-top">
                        <div class="col-header-row">
                            <div class="col-header-left">
                                <span class="col-name">Заметки</span>
                                <span class="col-sep">&middot;</span>
                                <span class="col-count">{{ notesStore.count }}</span>
                            </div>
                        </div>
                        <div class="col-line" />
                    </div>

                    <div class="notes-panel-body">
                        <CommentForm
                            v-model="newNoteText"
                            class="notes-new-form"
                            placeholder="Новая заметка..."
                            submit-label="Добавить"
                            :rows="3"
                            @submit="addNote"
                        />

                        <div class="notes-list">
                            <div
                                v-for="note in notesStore.items"
                                :key="note.id"
                                class="notes-item"
                            >
                                <div class="notes-item-header">
                                    <span class="notes-item-date">{{ formatDate(note.created_at) }}</span>
                                    <button
                                        class="notes-delete-btn"
                                        @click="deleteNote(note.id)"
                                    >
                                        <Icon name="close" :size="16" color="#828282" />
                                    </button>
                                </div>
                                <div class="notes-item-body">
                                    <p class="notes-item-text">{{ note.body }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useNotesStore } from '@/stores/notes'
import MainLayout from '@/components/layout/MainLayout.vue'
import Icon from '@/components/ui/Icon.vue'
import CommentForm from '@/components/ui/CommentForm.vue'

const notesStore = useNotesStore()
const newNoteText = ref('')

onMounted(() => {
    notesStore.fetchNotes()
})

async function addNote() {
    const text = newNoteText.value.trim()
    if (!text) return
    await notesStore.createNote(text)
    newNoteText.value = ''
}

async function deleteNote(id) {
    await notesStore.deleteNote(id)
}

function formatDate(dateStr) {
    if (!dateStr) return ''
    const d = new Date(dateStr)
    const now = new Date()
    const isToday = d.toDateString() === now.toDateString()
    const yesterday = new Date(now)
    yesterday.setDate(yesterday.getDate() - 1)
    const isYesterday = d.toDateString() === yesterday.toDateString()

    const time = d.toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' })

    if (isToday) return `Сегодня · ${time}`
    if (isYesterday) return `Вчера · ${time}`
    return d.toLocaleDateString('ru-RU', { day: 'numeric', month: 'long' }) + ` · ${time}`
}
</script>
