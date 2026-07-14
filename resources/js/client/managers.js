import { api } from '@/bootstrap'

export const managers = {
    getList() {
        return api.get('/managers')
    },
}
