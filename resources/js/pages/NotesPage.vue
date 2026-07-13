<template>
    <div class="page-root">
        <PortalHeader />

        <div class="content-area">
            <div class="nav-card-wrap">
                <NavTabs />
            </div>

            <div class="section-block notes-page">
                <div class="section-title-row">
                    <span class="section-title">Личные заметки</span>
                </div>

                <div class="notes-columns">
                    <div class="notes-panel">
                        <div class="notes-panel-top">
                            <div class="col-header-row">
                                <div class="col-header-left">
                                    <span class="col-name">Заметки</span>
                                    <span class="col-sep">&middot;</span>
                                    <span class="col-count">{{ notes.length }}</span>
                                </div>
                            </div>
                            <div class="col-line" />
                        </div>

                        <div class="notes-panel-body">
                            <div class="modal-comment-new notes-new-form">
                                <textarea
                                    ref="newNoteRef"
                                    v-model="newNoteText"
                                    class="modal-comment-input notes-new-textarea"
                                    placeholder="Новая заметка..."
                                    rows="3"
                                ></textarea>
                                <div class="modal-comment-new-bottom">
                                    <div class="modal-comment-divider" />
                                    <button
                                        class="modal-comment-send notes-add-btn"
                                        :disabled="!newNoteText.trim()"
                                        @click="addNote"
                                    >Добавить</button>
                                </div>
                            </div>

                            <div class="notes-list">
                                <div
                                    v-for="note in notes"
                                    :key="note.id"
                                    class="notes-item"
                                >
                                    <div class="notes-item-header">
                                        <span class="notes-item-date">{{ note.date }} &middot; {{ note.time }}</span>
                                        <button
                                            class="notes-delete-btn"
                                            @click="deleteNote(note.id)"
                                        >
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M3.66602 3.33301L12.9993 12.6663M3.66602 12.6663L12.9993 3.33301" stroke="#828282" stroke-width="1.5"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="notes-item-body">
                                        <p class="notes-item-text">{{ note.text }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import PortalHeader from '../components/layout/PortalHeader.vue'
import NavTabs from '../components/layout/NavTabs.vue'

const newNoteRef = ref(null)
const newNoteText = ref('')

const notes = ref([
    {
        id: 1,
        date: 'Сегодня',
        time: '10:30',
        text: 'Для работы системы у менеджера должна быть привязка его профиля на портале к его профилю в 1С',
    },
    {
        id: 2,
        date: 'Вчера',
        time: '14:55',
        text: '«Новую надежду» я впервые увидел в 1991 году в советском ещё кинотеатре. Картинка на экране была поменьше, чем при обычном кинопоказе, но и заметно побольше, чем в видеосалоне. Через пару дней после моей встречи с «Новой надеждой», утром в нашей квартире раздался телефонный звонок: «У нас в стране военный переворот, скорее включайте телевизор», – выпалил в прямой эфир взволнованный отец.',
    },
])

function addNote() {
    const text = newNoteText.value.trim()
    if (!text) return

    const now = new Date()
    const hours = String(now.getHours()).padStart(2, '0')
    const minutes = String(now.getMinutes()).padStart(2, '0')

    notes.value.unshift({
        id: Date.now(),
        date: 'Сегодня',
        time: `${hours}:${minutes}`,
        text,
    })

    newNoteText.value = ''
}

function deleteNote(id) {
    notes.value = notes.value.filter(n => n.id !== id)
}
</script>
