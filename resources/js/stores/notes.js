import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { notes as notesApi } from '@/client'

export const useNotesStore = defineStore('notes', () => {
    const items = ref([])
    const loading = ref(false)
    const error = ref(null)

    const count = computed(() => items.value.length)

    async function fetchNotes() {
        loading.value = true
        error.value = null
        try {
            const { data } = await notesApi.getList()
            items.value = data.data
        } catch (e) {
            error.value = e.response?.data?.message || 'Ошибка загрузки заметок'
        } finally {
            loading.value = false
        }
    }

    async function createNote(body) {
        const { data } = await notesApi.create(body)
        items.value.unshift(data.data)
        return data.data
    }

    async function updateNote(id, body) {
        const { data } = await notesApi.update(id, body)
        const idx = items.value.findIndex(n => n.id === id)
        if (idx !== -1) items.value[idx] = data.data
        return data.data
    }

    async function deleteNote(id) {
        await notesApi.delete(id)
        items.value = items.value.filter(n => n.id !== id)
    }

    function $reset() {
        items.value = []
        loading.value = false
        error.value = null
    }

    return {
        items,
        loading,
        error,
        count,
        fetchNotes,
        createNote,
        updateNote,
        deleteNote,
        $reset,
    }
})
