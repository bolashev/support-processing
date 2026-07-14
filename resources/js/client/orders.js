import { api } from '../bootstrap'

export const orders = {
    getList(params = {}) {
        return api.get('/orders', { params })
    },

    getById(id) {
        return api.get(`/orders/${id}`)
    },

    take(id) {
        return api.post(`/orders/${id}/take`)
    },

    return(id, comment) {
        return api.post(`/orders/${id}/return`, { comment })
    },

    updateField(id, field, value) {
        return api.put(`/orders/${id}/field`, { field, value })
    },

    addComment(id, body, isInternal = false) {
        return api.post(`/orders/${id}/comment`, { body, is_internal: isInternal })
    },
}
