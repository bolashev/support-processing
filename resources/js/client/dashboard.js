import { api } from '@/bootstrap'

export const dashboard = {
    getCounters() {
        return api.get('/dashboard')
    },
}
