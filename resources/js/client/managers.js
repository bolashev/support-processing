import { api } from '@/bootstrap'

export const managers = {
    getList(params = {}) {
        return api.get('/managers', { params })
    },
}
