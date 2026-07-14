import { api } from '@/bootstrap'

export const archive = {
    getList(params = {}) {
        return api.get('/archive', { params })
    },
}
