import { api } from '@/bootstrap'

export const notes = {
    getList() {
        return api.get('/notes')
    },

    create(body) {
        return api.post('/notes', { body })
    },

    update(id, body) {
        return api.put(`/notes/${id}`, { body })
    },

    delete(id) {
        return api.delete(`/notes/${id}`)
    },
}
