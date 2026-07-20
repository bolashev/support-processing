import { api } from '@/bootstrap'

export const auth = {
    logout() {
        return api.post('/auth/logout')
    },
}
